<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unit_number' => 'required|string|max:50',
            'floor' => 'nullable|integer',
            'area' => 'nullable|numeric|min:0',
            'parking_slots' => 'nullable|integer|min:0',
            'storerooms' => 'nullable|integer|min:0',
        ];
    }

}
