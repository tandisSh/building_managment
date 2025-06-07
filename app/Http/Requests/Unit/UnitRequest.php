<?php

namespace App\Http\Requests\Unit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'building_id'    => ['required', 'exists:buildings,id'],
            'unit_number'    => [
                'required',
                'string',
                'max:255',
                Rule::unique('units')->where(function ($query) {
                    return $query->where('building_id', $this->building_id);
                }),
            ],
            'floor'          => 'required|integer',
            'area'           => 'required|numeric|min:0',
            'parking_slots'  => 'nullable|integer|min:0',
            'storerooms'     => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'unit_number.unique' => 'این شماره واحد قبلاً در این ساختمان ثبت شده است.',
            'building_id.required' => 'شناسه ساختمان الزامی است.',
        ];
    }
}
