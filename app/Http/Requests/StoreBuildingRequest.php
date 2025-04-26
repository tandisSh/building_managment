<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBuildingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // اجازه همه کاربرهای لاگین شده
    }

    public function rules(): array
    {
        return [
            'building_name' => 'required|string|max:255',
            'address' => 'required|string',
            'number_of_floors' => 'required|integer|min:1',
            'number_of_units' => 'required|integer|min:1',
            'shared_utilities' => 'required|boolean',
            'document' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ];
    }

}
