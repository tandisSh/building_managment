<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class ResidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $resident = $this->route('resident');
        $residentId = is_object($resident) ? $resident->id : $resident;

        return [
            'unit_id'         => ['required', 'exists:units,id'],
            'name'            => ['required', 'string', 'max:255'],
            'phone'           => ['required', 'string', 'unique:users,phone,' . $residentId],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $residentId],
            'role'            => ['required', 'in:resident,owner,resident_owner'],
            'from_date'       => ['required', 'date'],
            'to_date'         => ['nullable', 'date', 'after:from_date'],
            'residents_count' => [
                'required_if:role,resident,resident_owner',
                'nullable', // برای نقش owner این فیلد اختیاری باشد
                'integer',
                'min:1'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'unit_id.required' => 'لطفاً واحد را انتخاب کنید.',
            'name.required' => 'وارد کردن نام الزامی است.',
            'phone.required' => 'وارد کردن شماره موبایل الزامی است.',
            'email.required' => 'وارد کردن  ایمیل الزامی است.',
            'phone.unique' => 'این شماره قبلاً ثبت شده است.',
            'email.email' => 'ایمیل وارد شده معتبر نیست.',
            'role.required' => 'لطفاً نقش را انتخاب کنید.',
            'residents_count.required_if' => 'تعداد افراد خانوار برای ساکن الزامی است.',
            'residents_count.min' => 'حداقل تعداد افراد باید 1 باشد.',
            'from_date.required' => 'تاریخ شروع سکونت الزامی است.',
            'to_date.after' => 'تاریخ پایان باید بعد از تاریخ شروع باشد.',
        ];
    }
}
