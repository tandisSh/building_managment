<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isManager();
    }

    public function rules(): array
    {
        return [
            'base_amount' => 'required|numeric|min:0',
            'water_cost' => 'nullable|numeric|min:0',
            'electricity_cost' => 'nullable|numeric|min:0',
            'gas_cost' => 'nullable|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'description' => 'nullable|string|max:255',
        ];
    }
}
