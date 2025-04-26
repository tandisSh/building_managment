<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            $user = Auth::user();

            // ریدایرکت بر اساس نقش کاربر
            return match (true) {
                $user->hasRole('super_admin') => redirect()->route('super_admin.dashboard'),
                $user->hasRole('manager') => redirect()->route('manager.dashboard'),
                $user->hasRole('resident') => redirect()->route('resident.dashboard'),
                default => redirect('/')
            };
        }

        return back()->withErrors(['phone' => 'اطلاعات ورود نامعتبر است.']);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'شما با موفقیت خارج شدید');
    }

    public function showManagerRegisterForm()
    {
        return view('auth.register.manager');
    }

    public function registerManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^09[0-9]{9}$/|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // اختصاص نقش مدیر
        $managerRole = Role::where('name', 'manager')->first();
        $user->roles()->attach($managerRole);

        Auth::login($user);

        return redirect()->route('manager.dashboard')
            ->with('success', 'ثبت‌نام شما با موفقیت انجام شد!');
    }
}
