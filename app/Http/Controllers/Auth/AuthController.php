<?php

namespace App\Http\Controllers\Auth;


use App\Http\Requests\RegisterManagerRequest;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('phone', 'password'))) {
            $user = Auth::user();

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

    public function registerManager(RegisterManagerRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $managerRole = Role::where('name', 'manager')->first();
        $user->roles()->attach($managerRole);

        Auth::login($user);

        return redirect()->route('manager.dashboard')->with('success', 'ثبت‌نام شما با موفقیت انجام شد!');
    }

}
