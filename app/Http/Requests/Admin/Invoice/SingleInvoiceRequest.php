<?php

namespace App\Http\Requests\Admin\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SingleInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'building_id' => 'required|exists:buildings,id',
            'unit_id' => [
                'required',
                'exists:units,id',
                Rule::exists('units', 'id')->where(function ($query) {
                    $query->where('building_id', $this->building_id);
                }),
            ],
            'title' => 'required|string|max:255',
            'type' => 'required|in:current,fixed',
            'amount' => 'required|numeric|min:1000',
            'due_date' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'building_id.required' => 'ساختمان الزامی است.',
            'building_id.exists' => 'ساختمان انتخاب‌شده معتبر نیست.',
            'unit_id.required' => 'واحد الزامی است.',
            'unit_id.exists' => 'واحد انتخاب‌شده معتبر نیست یا متعلق به این ساختمان نیست.',
            'title.required' => 'عنوان صورتحساب الزامی است.',
            'type.required' => 'نوع صورتحساب الزامی است.',
            'type.in' => 'نوع صورتحساب معتبر نیست.',
            'amount.required' => 'مبلغ الزامی است.',
            'amount.numeric' => 'مبلغ باید عددی باشد.',
            'amount.min' => 'مبلغ باید بیشتر از ۱۰۰۰ تومان باشد.',
            'due_date.required' => 'مهلت پرداخت الزامی است.',
            'due_date.after_or_equal' => 'تاریخ باید امروز یا بعد از امروز باشد.',
            'description.max' => 'توضیحات حداکثر می‌تواند ۱۰۰۰ کاراکتر باشد.',
        ];
    }
}
