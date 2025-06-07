<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class SingleInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:current,fixed',
            'amount' => 'required|numeric|min:1000',
            'due_date' => 'required|date|after:today',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'unit_id.required' => 'واحد الزامی است.',
            'unit_id.exists' => 'واحد انتخاب‌شده معتبر نیست.',
            'title.required' => 'عنوان صورتحساب الزامی است.',
            'type.required' => 'نوع صورتحساب الزامی است.',
            'type.in' => 'نوع صورتحساب معتبر نیست.',
            'amount.required' => 'مبلغ الزامی است.',
            'amount.numeric' => 'مبلغ باید عددی باشد.',
            'amount.min' => 'مبلغ باید بیشتر از ۱۰۰۰ تومان باشد.',
            'due_date.required' => 'مهلت پرداخت الزامی است.',
            'due_date.after' => 'تاریخ باید بعد از امروز باشد.',
            'description.max' => 'توضیحات حداکثر می‌تواند ۱۰۰۰ کاراکتر باشد.',
        ];
    }
}
