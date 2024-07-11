<?php

namespace App\Http\Requests\Api\v1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
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
            "email" => "required|email",
            "password" => "required|string|min:8|max:255"
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "email.required" => "Email is required",
            "email.email" => "Email must be a valid email address",

            "password.required" => "Password is required",
            "password.string" => "Password must be a string",
            "password.min" => "Password must be at least 8 characters",
            "password.max" => "Password must not be greater than 255 characters"
        ];
    }
}
