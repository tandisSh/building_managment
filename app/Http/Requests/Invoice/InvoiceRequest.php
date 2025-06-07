<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'base_amount' => ['required', 'numeric'],
            'due_date' => ['required', 'date'],
            'type' => ['required', 'in:current,fixed'],
            'distribution_type' => ['required', 'in:equal,per_person'],
            'fixed_percent' => ['required_if:distribution_type,per_person', 'numeric', 'min:1', 'max:100'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'لطفا عنوان صورتحساب را وارد کنید.',
            'title.string' => 'عنوان صورتحساب باید متنی باشد.',

            'base_amount.required' => 'لطفا مبلغ را وارد کنید.',
            'base_amount.numeric' => 'مبلغ باید عددی باشد.',

            'due_date.required' => 'لطفا مهلت پرداخت را وارد کنید.',
            'due_date.date' => 'فرمت مهلت پرداخت صحیح نیست.',

            'type.required' => 'نوع صورتحساب مشخص نشده است.',
            'type.in' => 'نوع صورتحساب نامعتبر است.',

            'distribution_type.required' => 'لطفا روش تقسیم هزینه را انتخاب کنید.',
            'distribution_type.in' => 'روش تقسیم هزینه انتخاب شده نامعتبر است.',

            'fixed_percent.required_if' => 'لطفا درصد پایه برای تقسیم را وارد کنید.',
            'fixed_percent.numeric' => 'درصد پایه باید عدد باشد.',
            'fixed_percent.min' => 'درصد پایه باید حداقل 1 باشد.',
            'fixed_percent.max' => 'درصد پایه نمی‌تواند بیشتر از 100 باشد.',

            'description.string' => 'توضیحات باید متنی باشد.',
        ];
    }
}
