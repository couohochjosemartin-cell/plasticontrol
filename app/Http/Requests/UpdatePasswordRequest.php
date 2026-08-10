<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'password_actual' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:password_actual',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password_actual.required' =>
                'Ingresa tu contraseña actual.',

            'password_actual.current_password' =>
                'La contraseña actual no es correcta.',

            'password.required' =>
                'Ingresa la nueva contraseña.',

            'password.min' =>
                'La nueva contraseña debe tener al menos 8 caracteres.',

            'password.confirmed' =>
                'La confirmación de la nueva contraseña no coincide.',

            'password.different' =>
                'La nueva contraseña debe ser diferente a la actual.',
        ];
    }
}