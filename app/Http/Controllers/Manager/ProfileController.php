<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('manager.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('manager.profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        
        $user->update($request->validated());

        return redirect()->route('manager.profile.show')->with('success', 'پروفایل با موفقیت به‌روزرسانی شد.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('manager.profile.edit')->with('success', 'رمز عبور با موفقیت تغییر کرد.');
    }
} 