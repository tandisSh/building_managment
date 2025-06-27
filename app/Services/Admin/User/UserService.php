<?php

namespace App\Services\Admin\User;

use App\Mail\SendInitialPasswordMail;
use App\Models\Building;
use App\Models\BuildingUser;
use App\Models\Role;
use App\Models\UnitUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
                        $q->where('role', 'owner');
                    } elseif ($role === 'resident') {
                        $q->where('role', 'resident');
                    } elseif ($role === 'resident_owner') {
                        $q->where(function ($q2) {
                            $q2->where('role', 'resident')->orWhere('role', 'owner');
                        });
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

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $userType = $data['user_type'] ?? 'normal';
            $password = Str::random(10);

            $user = User::firstOrCreate(
                ['phone' => $data['phone']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($password),
                    'status' => 'active',
                ]
            );

            if ($userType === 'manager') {
                $this->processBuildingManager($user, $data);
            } else {
                $this->processRegularUser($user, $data);
            }

            Mail::to($user->email)->send(new SendInitialPasswordMail($user, $password));

            return $user;
        });
    }

    private function processBuildingManager($user, array $data)
    {
        $role = Role::where('id', 2)->firstOrFail(); // نقش مدیر
        $user->roles()->sync([$role->id]);

        BuildingUser::firstOrCreate([
            'building_id' => $data['building_id'],
            'user_id' => $user->id,
            'role' => 'manager',
        ]);
    }

    private function processRegularUser($user, array $data)
    {
        $role = Role::where('id', 3)->firstOrFail(); // نقش کاربر عادی
        $user->roles()->sync([$role->id]);

        $unitRole = $data['role'];
        $this->checkDuplicateRole($data['unit_id'], $unitRole);

        if ($unitRole === 'both') {
            UnitUser::create([
                'unit_id' => $data['unit_id'],
                'user_id' => $user->id,
                'role' => 'owner',
                'status' => 'active',
                'from_date' => $data['from_date'],
            ]);
            UnitUser::create([
                'unit_id' => $data['unit_id'],
                'user_id' => $user->id,
                'role' => 'resident',
                'resident_count' => $data['resident_count'] ?? null,
                'from_date' => $data['from_date'],
                'to_date' => $data['to_date'] ?? null,
                'status' => 'active',
            ]);
        } else {
            UnitUser::create([
                'unit_id' => $data['unit_id'],
                'user_id' => $user->id,
                'role' => $unitRole,
                'resident_count' => $unitRole === 'resident' ? $data['resident_count'] : null,
                'from_date' => $data['from_date'],
                'to_date' => $unitRole === 'resident' ? $data['to_date'] : null,
                'status' => 'active',
            ]);
        }

        // حذف ردیف building_user برای کاربران عادی، چون فقط برای manager نیازه
        // اگر بخوای برای کاربران عادی از 'other' استفاده کنی، می‌تونی این خط رو فعال کنی:
        // BuildingUser::firstOrCreate([
        //     'building_id' => $data['building_id'],
        //     'user_id' => $user->id,
        //     'role' => 'other',
        // ]);
    }

    private function checkDuplicateRole($unitId, $role)
    {
        $existingRoles = ['resident', 'owner'];
        if ($role === 'both') {
            foreach ($existingRoles as $r) {
                if (UnitUser::where('unit_id', $unitId)->where('role', $r)->where('status', 'active')->exists()) {
                    throw ValidationException::withMessages(['role' => "قبلاً یک {$r} برای این واحد ثبت شده است."]);
                }
            }
        } else {
            if (UnitUser::where('unit_id', $unitId)->where('role', $role)->where('status', 'active')->exists()) {
                throw ValidationException::withMessages(['role' => "قبلاً یک {$role} برای این واحد ثبت شده است."]);
            }
        }
    }

    public function getUserForEdit($id)
    {
        return User::with(['roles', 'unitUsers.unit', 'buildingUsers'])->findOrFail($id);
    }

    public function updateUser($id, array $data)
    {
        DB::transaction(function () use ($id, $data) {
            $user = User::findOrFail($id);
            $userType = $data['user_type'] ?? 'normal';
            $user->update([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'status' => $data['status'] ?? 'active',
            ]);

            if (!empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            if ($userType === 'manager') {
                $user->buildingUsers()->updateOrCreate(
                    ['building_id' => $data['building_id']],
                    ['role' => 'manager']
                );
            } else {
                $unitRole = $data['role'];
                $user->unitUsers()->delete(); // حذف نقش‌های قبلی
                $this->checkDuplicateRole($data['unit_id'], $unitRole);

                if ($unitRole === 'both') {
                    UnitUser::create([
                        'unit_id' => $data['unit_id'],
                        'user_id' => $user->id,
                        'role' => 'owner',
                        'status' => 'active',
                        'from_date' => $data['from_date'],
                    ]);
                    UnitUser::create([
                        'unit_id' => $data['unit_id'],
                        'user_id' => $user->id,
                        'role' => 'resident',
                        'resident_count' => $data['resident_count'],
                        'from_date' => $data['from_date'],
                        'to_date' => $data['to_date'],
                        'status' => 'active',
                    ]);
                } else {
                    UnitUser::create([
                        'unit_id' => $data['unit_id'],
                        'user_id' => $user->id,
                        'role' => $unitRole,
                        'resident_count' => $unitRole === 'resident' ? $data['resident_count'] : null,
                        'from_date' => $data['from_date'],
                        'to_date' => $unitRole === 'resident' ? $data['to_date'] : null,
                        'status' => 'active',
                    ]);
                }

                // حذف یا به‌روزرسانی building_user برای کاربران عادی
                $user->buildingUsers()->where('user_id', $user->id)->delete();
                // اگر بخوای 'other' برای کاربران عادی ثبت کنی، این خط رو فعال کن:
                // $user->buildingUsers()->updateOrCreate(
                //     ['building_id' => $data['building_id']],
                //     ['role' => 'other']
                // );
            }
        });
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->roles()->detach();
        $user->unitUsers()->delete();
        $user->buildingUsers()->delete();
        $user->delete();
    }
}
