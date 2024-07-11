<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() == null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'role_id' => 'nullable|string|max:255'
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
            'name.required' => 'Nama tidak boleh kosong',
            'name.max' => 'Nama terlalu panjang (max: 255 karakter)',

            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'email.max' => 'Email terlalu panjang (max: 255 karakter)',
            'email.unique' => 'Email yang anda masukkan sudah terdaftar',

            'password.required' => 'Password tidak boleh kosong',
            'password.string' => 'Password tidak valid',
            'password.min' => 'Password yang anda masukkan terlalu lemah (min: 8 karakter)',

            'role_id.string' => 'Role tidak valid',
            'role_id.max' => 'Role terlalu panjang (max: 255 karakter)',
            'role_id.exists' => 'Role yang anda masukkan tidak valid',
        ];
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 1)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages([
            'email' => "Anda telah mencoba terlalu sering. Silahkan coba lagi dalam $seconds detik.",
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate($this->ip());
    }
}
