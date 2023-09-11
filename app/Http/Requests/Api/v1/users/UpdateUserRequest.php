<?php

namespace App\Http\Requests\Api\v1\users;

use App\Rules\ValidateRoleIds;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email'    => [
                'required',
                'min:5',
                'email',
                Rule::unique('users', 'email')->ignore($this->id, 'user_id'),
            ],
            'role_ids' => ['required', 'json', new ValidateRoleIds()],
            'staff_id' => 'nullable|exists:staff,staff_id',
            'flag'     => 'required|string|max:255'
        ];
    }
}
