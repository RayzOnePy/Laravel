<?php

namespace App\Http\Requests;

use App\Http\Custom\ValidationField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class RegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(3)->mixedCase()->numbers()],
            'first_name' => ['required', 'string', 'min:2'],
            'last_name' => ['required', 'string'],
        ];
    }
}
