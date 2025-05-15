<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $role = $this->input('role', 'admin');

        if ($role === 'admin') {
            $passwordRules = [
                'required',
                'string',
                Password::min(10)->mixedCase()->numbers()->symbols()->uncompromised(),
                'confirmed',
            ];
        } else {
            $passwordRules = [
                'required',
                'string',
                Password::min(6)->mixedCase()->uncompromised(),
                'confirmed',
            ];
        }
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required',
            'password' => $passwordRules,
        ];
    }
}
