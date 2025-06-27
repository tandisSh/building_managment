<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules()
    {
        $userType = $this->input('user_type');

        return [
            'user_type' => ['required', Rule::in(['manager', 'normal'])],
        ] + ($userType === 'manager' ? $this->managerRules() : $this->normalRules());
    }

    private function managerRules()
    {
        $userId = $this->route('user');
        return [
            'building_id' => ['required', 'exists:buildings,id', function ($attribute, $value, $fail) {
                $managerExists = \App\Models\BuildingUser::where('building_id', $value)
                    ->where('role', 'manager')
                    ->exists();
                if ($managerExists) {
                    $fail('برای این ساختمان مدیر ثبت شده است.');
                }
            }],
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', Rule::unique('users', 'phone')->ignore($userId)],
            'status' => ['required', Rule::in(['active', 'inactive'])], // اضافه کردن status برای مدیر
        ];
    }

    private function normalRules()
    {
        $userId = $this->route('user');
        return [
            'building_id' => 'required|exists:buildings,id',
            'unit_id' => 'required|exists:units,id',
            'role' => ['required', Rule::in(['resident', 'owner', 'both'])],
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', Rule::unique('users', 'phone')->ignore($userId)],
            'resident_count' => ['nullable', 'integer', 'min:1', 'required_if:role,resident,both'],
            'from_date' => ['required', 'date'],
            'to_date' => ['nullable', 'date', 'after:from_date', 'required_if:role,resident,both'],
            'status' => ['required', Rule::in(['active', 'inactive'])], // اصلاح قانون status
        ];
    }

    public function messages()
    {
        return [
            'user_type.required' => 'نوع کاربر الزامی است.',
            'user_type.in' => 'نوع کاربر معتبر نیست.',
            'building_id.required' => 'انتخاب ساختمان الزامی است.',
            'unit_id.required' => 'انتخاب واحد الزامی است.',
            'role.required' => 'انتخاب نقش الزامی است.',
            'role.in' => 'نقش انتخاب شده معتبر نیست.',
            'name.required' => 'نام کاربر الزامی است.',
            'email.email' => 'ایمیل وارد شده معتبر نیست.',
            'email.unique' => 'ایمیل وارد شده قبلاً ثبت شده است.',
            'phone.unique' => 'شماره تلفن وارد شده قبلاً ثبت شده است.',
            'resident_count.integer' => 'تعداد افراد خانوار باید عدد باشد.',
            'resident_count.min' => 'تعداد افراد خانوار نمی‌تواند کمتر از 1 باشد.',
            'resident_count.required_if' => 'تعداد افراد خانوار برای نقش ساکن الزامی است.',
            'from_date.required' => 'تاریخ شروع سکونت الزامی است.',
            'to_date.required_if' => 'تاریخ پایان سکونت برای نقش ساکن الزامی است.',
            'to_date.after' => 'تاریخ پایان باید بعد از تاریخ شروع باشد.',
            'status.required' => 'وضعیت کاربر الزامی است.',
            'status.in' => 'وضعیت کاربر باید یکی از مقادیر فعال یا غیرفعال باشد.',
        ];
    }
}
