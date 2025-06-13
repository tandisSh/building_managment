<?php

namespace App\Http\Requests\Admin\Building;

use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // اگر لازم است، شرط دسترسی سوپر ادمین اضافه شود
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'number_of_floors' => 'required|integer|min:1',
            'number_of_units' => 'required|integer|min:1',
            'shared_electricity' => 'nullable|boolean',
            'shared_water' => 'nullable|boolean',
            'shared_gas' => 'nullable|boolean',
            'manager_id' => 'required|exists:users,id',
        ];

        if ($this->isMethod('POST')) {
            $rules['document'] = 'required|file|mimes:pdf,jpg,png|max:2048';
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['document'] = 'nullable|file|mimes:pdf,jpg,png|max:2048';
        }

        return $rules;
    }
}
