<?php

namespace App\Http\Requests\Api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'email'    => 'required|min:5|email|unique:users,email',
            'password' => 'required|confirmed|min:8|max:255|alpha_num',
            'image'    => 'nullable',
            'is_banned'=> 'nullable',
        ];
    }
}
