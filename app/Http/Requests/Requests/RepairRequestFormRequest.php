<?php
namespace App\Http\Requests\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepairRequestFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان درخواست الزامی است.',
            'description.required' => 'توضیحات الزامی است.',
            'description.min' => 'توضیحات باید حداقل ۱۰ کاراکتر باشد.',
        ];
    }
}
