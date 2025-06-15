<?php

namespace App\Services\Admin\User;

use App\Models\User;
use App\Models\Building;
use App\Models\Unit;
use App\Models\UnitUser;
use App\Models\BuildingUser;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class UserService
{
    public function getFilteredUsers(array $filters)
    {
        return User::with(['roles', 'units.building'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            })
            ->when($filters['building_id'] ?? null, function ($query, $buildingId) {
                $query->whereHas('units.building', function ($q) use ($buildingId) {
                    $q->where('id', $buildingId);
                });
            })
            ->when($filters['role'] ?? null, function ($query, $role) {
                $query->whereHas('unitUsers', function ($q) use ($role) {
                    if ($role === 'owner') {
                        $q->where('role', 'مالک');
                    } elseif ($role === 'resident') {
                        $q->where('role', 'ساکن');
                    } elseif ($role === 'resident_owner') {
                        $q->where('role', 'مالک و ساکن');
                    }
                });
            })
            ->latest()
            ->paginate(15);
    }

    public function getBuildingsForForm()
    {
        return Building::all();
    }

    // public function createUser(array $data): User
    // {
    //     return DB::transaction(function () use ($data) {
    //         // ایجاد یا یافتن کاربر
    //         $user = $this->findOrCreateUser($data);

    //         // تخصیص نقش سیستمی
    //         $this->assignSystemRole($user, $data['system_role']);

    //         // اگر کاربر عادی است (نقش 3)
    //         if ($data['system_role'] == 3) {
    //             $this->processRegularUser($user, $data);
    //         }
    //         // اگر مدیر ساختمان است (نقش 2)
    //         elseif ($data['system_role'] == 2) {
    //             $this->processBuildingManager($user, $data);
    //         }

    //         return $user;
    //     });
    // }


    // private function assignSystemRole(User $user, int $roleId): void
    // {
    //     $user->roles()->sync([$roleId]);
    // }

    // private function processRegularUser(User $user, array $data): void
    // {
    //     // تبدیل نقش به فرمت مناسب
    //     $role = $data['unit_role'] === 'resident_owner' ? 'both' : $data['unit_role'];

    //     // بررسی تکراری نبودن نقش
    //     if ($role === 'both') {
    //         $this->checkDuplicateRole($data['unit_id'], 'owner');
    //         $this->checkDuplicateRole($data['unit_id'], 'resident');
    //     } else {
    //         $this->checkDuplicateRole($data['unit_id'], $role);
    //     }

    //     // تخصیص نقش‌های واحد
    //     if ($role === 'both') {
    //         // ثبت resident
    //         $this->attachToUnit($user->id, array_merge($data, [
    //             'role' => 'resident',
    //         ]));

    //         // ثبت owner
    //         $this->attachToUnit($user->id, array_merge($data, [
    //             'role' => 'owner',
    //             'resident_count' => null,
    //         ]));
    //     } else {
    //         $this->attachToUnit($user->id, array_merge($data, ['role' => $role]));
    //     }

    //     // ثبت در building_user
    //     $this->attachToBuilding($user->id, $data['unit_id']);
    // }

    // private function processBuildingManager(User $user, array $data): void
    // {
    //     // فقط برای مدیر ساختمان، ساختمان را ثبت می‌کنیم
    //     foreach ($data['building_id'] as $buildingId) {
    //         BuildingUser::create([
    //             'building_id' => $buildingId,
    //             'user_id' => $user->id,
    //             'role' => 'manager'
    //         ]);
    //     }
    // }


    // private function attachToUnit($userId, array $data): void
    // {
    //     $unitData = [
    //         'unit_id' => $data['unit_id'],
    //         'user_id' => $userId,
    //         'role' => $data['role'],
    //         'from_date' => $data['from_date'] ?? now(),
    //         'status' => 'active'
    //     ];

    //     if ($data['role'] === 'resident') {
    //         $unitData['resident_count'] = $data['resident_count'];
    //     }

    //     UnitUser::create($unitData);
    // }

    // private function attachToBuilding($userId, $unitId): void
    // {
    //     $unit = Unit::find($unitId);
    //     if ($unit && $unit->building_id) {
    //         BuildingUser::firstOrCreate([
    //             'building_id' => $unit->building_id,
    //             'user_id' => $userId,
    //             'role' => 'resident'
    //         ]);
    //     }
    // }

    // private function findOrCreateUser(array $data): User
    // {
    //     $user = User::where('phone', $data['phone'])->first();

    //     if (!$user) {
    //         $password = Str::random(10);
    //         $user = User::create([
    //             'name' => $data['name'],
    //             'phone' => $data['phone'],
    //             'email' => $data['email'],
    //             'password' => Hash::make($password),
    //             'status' => 'active'
    //         ]);

    //         Mail::to($user->email)->send(new \App\Mail\SendInitialPasswordMail($user, $password));
    //     }

    //     return $user;
    // }

    // private function processUnitAssignment(User $user, array $data): void
    // {
    //     $role = $data['unit_role'];

    //     if ($role === 'resident_owner') {
    //         $this->checkDuplicateRole($data['unit_id'], 'owner');
    //         $this->checkDuplicateRole($data['unit_id'], 'resident');

    //         // ثبت مالک
    //         UnitUser::create([
    //             'unit_id' => $data['unit_id'],
    //             'user_id' => $user->id,
    //             'role' => 'owner',
    //             'from_date' => $data['from_date'],
    //             'status' => 'active'
    //         ]);

    //         // ثبت ساکن
    //         UnitUser::create([
    //             'unit_id' => $data['unit_id'],
    //             'user_id' => $user->id,
    //             'role' => 'resident',
    //             'resident_count' => $data['resident_count'],
    //             'from_date' => $data['from_date'],
    //             'to_date' => $data['to_date'],
    //             'status' => 'active'
    //         ]);
    //     } else {
    //         $this->checkDuplicateRole($data['unit_id'], $role);

    //         $unitData = [
    //             'unit_id' => $data['unit_id'],
    //             'user_id' => $user->id,
    //             'role' => $role,
    //             'from_date' => $data['from_date'],
    //             'status' => 'active'
    //         ];

    //         if ($role === 'resident') {
    //             $unitData['resident_count'] = $data['resident_count'];
    //             $unitData['to_date'] = $data['to_date'];
    //         }

    //         UnitUser::create($unitData);
    //     }
    // }

    // private function checkDuplicateRole($unitId, $role): void
    // {
    //     if (UnitUser::where('unit_id', $unitId)
    //                ->where('role', $role)
    //                ->exists()) {
    //         $validator = Validator::make([], []);
    //         $label = $role === 'owner' ? 'مالک' : 'ساکن';
    //         $validator->errors()->add('role', "قبلاً یک {$label} برای این واحد ثبت شده است.");
    //         throw new ValidationException($validator);
    //     }
    // }

    public function getUserForEdit(string $id): User
    {
        return User::with(['roles', 'units'])->findOrFail($id);
    }

    public function updateUser(string $id, array $data): void
    {
        DB::transaction(function () use ($id, $data) {
            $user = User::findOrFail($id);

            $user->update([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'status' => $data['status']
            ]);

            if (!empty($data['password'])) {
                $user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }

            // Update roles
            if (!empty($data['role_id'])) {
                $user->roles()->sync([$data['role_id']]);
            }

            // Update units
            if (!empty($data['unit_id'])) {
                $user->units()->sync([$data['unit_id'] => [
                    'role' => $data['unit_role'],
                    'status' => $data['status']
                ]]);
            }
        });
    }

    public function deleteUser(string $id): void
    {
        $user = User::findOrFail($id);
        $user->roles()->detach();
        $user->units()->detach();
        $user->delete();
    }
}
