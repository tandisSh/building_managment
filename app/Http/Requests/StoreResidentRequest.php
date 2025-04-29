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
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:resident,owner',
            'from_date' => 'required|date',
            'to_date' => 'nullable|date|after:from_date',
        ];
        // return [
        //     'name' => 'required|string|max:255',
        //     'mobile' => 'required|string|max:20',
        //     'email' => 'required|email|max:255',
        //     'role' => 'required|in:resident,owner',
        //     'from_date' => 'required|date',
        //     'to_date' => 'nullable|date|after_or_equal:from_date',
        // ];
    }
}
