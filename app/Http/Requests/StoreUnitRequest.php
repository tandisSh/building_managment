<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'unit_number'    => 'required|string|max:255',
            'floor'          => 'nullable|integer',
            'area'           => 'nullable|numeric|min:0',
            'parking_slots'  => 'nullable|integer|min:0',
            'storerooms'     => 'nullable|integer|min:0',
        ];
    }

}
