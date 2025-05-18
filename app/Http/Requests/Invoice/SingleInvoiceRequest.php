<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class SingleInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
        'unit_id' => 'required|exists:units,id',
        'type' => 'required|in:current,fixed',
        'title' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'due_date' => 'required|date|after:today',
        'description' => 'nullable|string|max:255',

        ];
    }
}

