<?php

namespace App\Http\Requests\Api\v1\author;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGenresRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|min:3|max:255',
            'image'    => 'nullable|mimes:jpg,png,webp',
        ];
    }
}
