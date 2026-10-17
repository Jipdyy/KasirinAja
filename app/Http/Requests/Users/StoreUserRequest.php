<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'      =>  ['required', 'string', 'max:255'],
            'email'     =>  ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'role'      =>  ['required', 'string', Rule::exists('roles', 'name')],
            'password'  =>  ['required', 'string', 'min:8', 'confirmed'],
            'is_active' =>  ['required', 'boolean'],
        ];
    }
}
