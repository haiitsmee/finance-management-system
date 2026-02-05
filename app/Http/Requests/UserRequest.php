<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'username' => 'required|string|unique:users,username',
            'role' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required',
            'businesses' => 'sometimes|array',
            'businesses.*' => 'exists:businesses,id'
        ];
    }

    public function messages(): array
    {
        return [
            'businesses.required' => 'Pilih setidaknya satu bisnis.',
            'businesses.*.exists' => 'Salah satu bisnis yang dipilih tidak valid.',
        ];
    }
}
