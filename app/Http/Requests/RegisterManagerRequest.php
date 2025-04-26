<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterManagerRequest extends FormRequest
{
    public function authorize()
    {
        return true; // همه بتونن ثبت‌نام کنن
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

}

