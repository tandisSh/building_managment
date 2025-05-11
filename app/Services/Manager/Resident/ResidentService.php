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
        $this->checkDuplicateRole($data['unit_id'], $data['role']);

        $user = $this->findOrCreateUser($data);
        $this->attachToUnit($user->id, $data);
        $this->attachToBuilding($user->id, $data['unit_id']);

        return $user;
    }

    public function update(User $user, array $data): void
    {
        $user->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ]);

        $unitUser = UnitUser::where('user_id', $user->id)
            ->where('unit_id', $data['unit_id'])
            ->first();

        if ($unitUser) {
            $unitUser->update([
                'role' => $data['role'],
                'from_date' => $data['from_date'],
                'to_date' => $data['to_date'],
            ]);
        } else {
            $this->checkDuplicateRole($data['unit_id'], $data['role'], $user->id);
            $this->attachToUnit($user->id, $data);
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
            $label = $role === 'owner' ? 'مالک' : 'مستاجر';
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
        UnitUser::create([
            'unit_id' => $data['unit_id'],
            'user_id' => $userId,
            'role' => $data['role'],
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
        ]);
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
}
