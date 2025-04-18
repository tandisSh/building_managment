<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^09[0-9]{9}$/',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt($request->only('phone', 'password'))) {
            $user = Auth::user()->with('roles')->first(); // بارگذاری رابطه roles

            if ($user->hasRole('super_admin')) {
                return redirect()->route('super_admin.dashboard');
            }

            if ($user->hasRole('manager')) {
                return redirect()->route('manager.dashboard');
            }

            if ($user->hasRole('resident')) {
                return redirect()->route('resident.dashboard');
            }

            return redirect('/');
        }

        return back()->withErrors(['phone' => 'اطلاعات ورود نامعتبر است.']);
    }
}

    // خروج
    // public function logout()
    // {
    //     Auth::logout();
    //     return redirect()->route('home');
    // }

    // // نمایش فرم ثبت‌نام مدیر ساختمان
    // public function showManagerRegisterForm()
    // {
    //     return view('auth.register_manager');
    // }

    // // ثبت‌نام مدیر
    // public function registerManager(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string',
    //         'phone' => 'required|unique:users,phone',
    //         'password' => 'required|min:6|confirmed',
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'phone' => $request->phone,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     // نقش مدیر را به کاربر اختصاص می‌دهیم
    //     $managerRole = Role::where('name', 'manager')->first();
    //     $user->roles()->attach($managerRole);

    //     Auth::login($user);
    //     return redirect()->route('manager.dashboard');
    // }

