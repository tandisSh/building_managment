<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class ResidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $resident = $this->route('resident');

        $residentId = is_object($resident) ? $resident->id : $resident;

        return [
            'unit_id'    => 'required|exists:units,id',
            'name'       => 'required|string|max:255',
            'phone'      => 'required|string|unique:users,phone,' . $residentId,
            'email'      => 'required|email|max:255|unique:users,email,' . $residentId,
            'role'       => 'required|in:resident,owner',
            'from_date'  => 'required|date',
            'to_date'    => 'nullable|date|after:from_date',
        ];
    }
}
