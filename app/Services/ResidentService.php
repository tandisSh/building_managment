<?php

namespace App\Services;

use App\Models\User;
use App\Models\UnitUser;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ResidentService
{
    public function createResident(array $data, $unitId)
    {
        // 1. بررسی اینکه برای این واحد این نقش قبلاً ثبت نشده
        $existing = \App\Models\UnitUser::where('unit_id', $unitId)
            ->where('role', $data['role'])
            ->first();

        if ($existing) {
            $validator = Validator::make([], []);
            $roleLabel = $data['role'] === 'owner' ? 'مالک' : 'مستاجر';
            $validator->errors()->add('role', "قبلاً یک {$roleLabel} برای این واحد ثبت شده است.");
            throw new ValidationException($validator);
        }

        // 2. بررسی وجود کاربر
        $user = User::where('phone', $data['phone'])->orWhere('email', $data['email'])->first();

        if (!$user) {
            $randomPassword = Str::random(10); // رمز رندوم
            $user = User::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'password' => Hash::make($randomPassword),
            ]);

            Mail::to($user->email)->send(new \App\Mail\SendInitialPasswordMail($user, $randomPassword));
        }

        // 3. اتصال به واحد
        UnitUser::create([
            'unit_id' => $unitId,
            'user_id' => $user->id,
            'role' => $data['role'],
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
        ]);

        // 4. اتصال به ساختمان
        $unit = \App\Models\Unit::find($unitId);
        if ($unit && $unit->building_id) {
            \App\Models\BuildingUser::firstOrCreate([
                'building_id' => $unit->building_id,
                'user_id' => $user->id,
            ]);
        }

        return $user;
    }

}
