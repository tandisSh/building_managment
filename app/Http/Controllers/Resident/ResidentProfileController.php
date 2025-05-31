<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\ProfileUpdateRequest;
use App\Services\Resident\Profile\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ResidentProfileController extends Controller
{
    protected $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function show()
    {
        $user = Auth::user();
        return view('resident.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('resident.profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        $this->service->update($user, $request->validated());

        return redirect()->route('resident.profile.show')->with('success', 'پروفایل با موفقیت به‌روزرسانی شد.');
    }
public function updatePassword(Request $request)
{
    $request->validate([
        'password' => ['required', 'confirmed', Password::min(8)],
    ]);

    $user = auth()->user();
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('resident.profile.edit')->with('success', 'رمز عبور با موفقیت تغییر کرد.');
}

}
