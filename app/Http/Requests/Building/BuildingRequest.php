<?php

namespace App\Http\Requests\Building;

use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'building_name' => 'required|string|max:255',
            'address' => 'required|string',
            'number_of_floors' => 'required|integer|min:1',
            'number_of_units' => 'required|integer|min:1',
            'shared_utilities' => 'required|boolean',
        ];

        if ($this->isMethod('POST')) {
            $rules['document'] = 'required|file|mimes:pdf,jpg,png|max:2048';
        }

        return $rules;
    }
}
