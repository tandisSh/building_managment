<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResidentRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'role' => 'required|in:resident,owner',
            'from_date' => 'required|date',
            'to_date' => 'nullable|date|after:from_date',
        ];
    }
}
