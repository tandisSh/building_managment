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
            'fixed_percent' => ['required_if:distribution_type,per_person', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
        ];
    }
}
