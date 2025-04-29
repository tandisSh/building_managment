<?php

namespace App\Services;

use App\Models\User;
use App\Models\UnitUser;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
class ResidentService
{

    public function createResident(array $data, $unitId)
    {
        $user = User::where('phone', $data['phone'])->orWhere('email', $data['email'])->first();

        if (!$user) {
            $randomPassword = Str::random(10); // ساخت رمز ۱۰ رقمی رندوم

            $user = User::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'password' => Hash::make($randomPassword),
            ]);

            // ارسال ایمیل رمز عبور
            Mail::to($user->email)->send(new \App\Mail\SendInitialPasswordMail($user, $randomPassword));
        }

        // ثبت در جدول unit_user
        UnitUser::create([
            'unit_id' => $unitId,
            'user_id' => $user->id,
            'role' => $data['role'],
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
        ]);

        // ثبت در جدول building_user
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
