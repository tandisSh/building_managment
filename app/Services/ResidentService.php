<?php

namespace App\Services;

use App\Models\User;
use App\Models\UnitUser;
use Illuminate\Support\Facades\Password;

class ResidentService
{
    public function createResident(array $data, $unitId)
    {
        $user = User::where('phone', $data['phone'])->orWhere('email', $data['email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'password' => null,
            ]);

            Password::broker()->sendResetLink(['email' => $user->email]);
        }

        UnitUser::create([
            'unit_id' => $unitId,
            'user_id' => $user->id,
            'role' => $data['role'],
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
        ]);

        return $user;
    }
}
