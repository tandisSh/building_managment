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
            'base_amount' => 'required|numeric|min:0',
            'title' => 'required|string|max:255',
            'due_date' => 'required|date|after:today',
            'description' => 'nullable|string|max:255',
            'type' => ['required', 'in:current,fixed'],
        ];
    }
}
