<?php

namespace App\Services\Manager\Resident;

use App\Models\User;
use App\Models\UnitUser;
use App\Models\BuildingUser;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ResidentService
{
    public function create(array $data): User
    {
        // تبدیل مقدار role به 'both' اگر مقدار ارسال شده 'resident_owner' باشد
        $role = $data['role'] === 'resident_owner' ? 'both' : $data['role'];

        // بررسی تکراری نبودن نقش
        if ($role === 'both') {
            $this->checkDuplicateRole($data['unit_id'], 'owner');
            $this->checkDuplicateRole($data['unit_id'], 'resident');
        } else {
            $this->checkDuplicateRole($data['unit_id'], $role);
        }

        // یافتن یا ایجاد کاربر
        $user = $this->findOrCreateUser($data);

        // تخصیص نقش‌ها و ثبت در unit_user
        if ($role === 'both') {
            if (!$user->hasRole('resident')) $user->assignRole('resident');
            if (!$user->hasRole('owner')) $user->assignRole('owner');

            // ثبت resident
            $this->attachToUnit($user->id, array_merge($data, [
                'role' => 'resident',
            ]));

            // ثبت owner با مقدار null برای resident_count
            $this->attachToUnit($user->id, array_merge($data, [
                'role' => 'owner',
                'resident_count' => null,
            ]));
        } else {
            if ($role === 'resident' && !$user->hasRole('resident')) {
                $user->assignRole('resident');
            }
            if ($role === 'owner' && !$user->hasRole('owner')) {
                $user->assignRole('owner');
            }

            $this->attachToUnit($user->id, array_merge($data, ['role' => $role]));
        }

        // ثبت در building_user
        $this->attachToBuilding($user->id, $data['unit_id']);

        return $user;
    }

    public function update(User $user, array $data): void
    {
        $user->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'status' => $data['status'],
        ]);

        $unitUser = UnitUser::where('user_id', (int) $user->id)
            ->where('unit_id', (int) $data['unit_id'])
            ->where('role', $data['role'])
            ->first();

        if ($unitUser) {
            $unitUser->update([
                'from_date' => $data['from_date'],
                'to_date' => $data['to_date'],
                'resident_count' => $data['role'] === 'resident' ? ($data['resident_count'] ?? 1) : null,
                'status' => $data['status'] === 'inactive' ? 'inactive' : $unitUser->status,
            ]);
        } else {
            $this->checkDuplicateRole($data['unit_id'], $data['role'], $user->id);
            $this->attachToUnit($user->id, $data);
        }

        if ($data['role'] === 'resident' && !$user->hasRole('resident')) {
            $user->assignRole('resident');
        }
        if ($data['role'] === 'owner' && !$user->hasRole('owner')) {
            $user->assignRole('owner');
        }

        $this->attachToBuilding($user->id, $data['unit_id']);
    }

    private function checkDuplicateRole($unitId, $role, $excludeUserId = null)
    {
        $query = UnitUser::where('unit_id', $unitId)->where('role', $role);
        if ($excludeUserId) {
            $query->where('user_id', '!=', $excludeUserId);
        }
        if ($query->exists()) {
            $validator = Validator::make([], []);
            $label = $role === 'owner' ? 'مالک' : 'ساکن';
            $validator->errors()->add('role', "قبلاً یک {$label} برای این واحد ثبت شده است.");
            throw new ValidationException($validator);
        }
    }
    private function findOrCreateUser(array $data): User
    {
        $user = User::where('phone', $data['phone'])->orWhere('email', $data['email'])->first();

        if (!$user) {
            $password = Str::random(10);
            $user = User::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'password' => Hash::make($password),
            ]);

            Mail::to($user->email)->send(new \App\Mail\SendInitialPasswordMail($user, $password));
        }

        return $user;
    }

    private function attachToUnit($userId, array $data): void
    {
        $unitUser = new UnitUser([
            'unit_id' => $data['unit_id'],
            'user_id' => $userId,
            'role' => $data['role'],
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
        ]);

        if ($data['role'] === 'resident') {
            $unitUser->resident_count = $data['resident_count'] ?? null;
        }

        $unitUser->save();
    }

    private function attachToBuilding($userId, $unitId): void
    {
        $unit = Unit::find($unitId);
        if ($unit && $unit->building_id) {
            BuildingUser::firstOrCreate([
                'building_id' => $unit->building_id,
                'user_id' => $userId,
            ]);
        }
    }
public function getFilteredResidents($filters, $buildingId)
{
    $unitUsers = UnitUser::with(['user', 'unit'])
        ->whereHas('unit', fn($q) => $q->where('building_id', $buildingId))
        ->when($filters['search'] ?? null, function ($q, $search) {
            $q->whereHas('user', function ($q2) use ($search) {
                $q2->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        })
        ->when($filters['role'] ?? null, fn($q, $role) => $q->where('role', $role))
        ->when($filters['unit_id'] ?? null, fn($q, $unitId) => $q->where('unit_id', $unitId))
        ->when($filters['status'] ?? null, function ($q, $status) {
            // فقط از status کاربر اصلی استفاده کنیم
            $q->whereHas('user', fn($q2) => $q2->where('status', $status));
        })
        ->get();

    // گروه‌بندی بر اساس user_id و unit_id
    $grouped = $unitUsers
        ->groupBy(fn($item) => $item->user_id . '-' . $item->unit_id)
        ->map(function ($items) {
            /** @var \Illuminate\Support\Collection $items */
            $first = $items->first();
            $roles = $items->pluck('role')->unique()->sort()->values();

            $combinedRole = match (true) {
                $roles->contains('resident') && $roles->contains('owner') => 'مالک و ساکن',
                $roles->contains('owner') => 'مالک',
                $roles->contains('resident') => 'ساکن',
                default => implode('، ', $roles->toArray()),
            };

            return (object)[
                'user' => $first->user,
                'unit' => $first->unit,
                'roles' => $combinedRole,
                'created_at' => $first->created_at,
                'from_date' => $first->from_date,
                'to_date' => $first->to_date,
                'status' => $first->user->status,
                'resident_count' => $items->where('role', 'resident')->first()->resident_count ?? null,
            ];
        })->values();

    return $grouped;
}
}
