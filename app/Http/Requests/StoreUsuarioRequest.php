<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador() ?? false;
    }

    public function rules(): array
    {
        return [
            'rol_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id'),
            ],

            'nombre_completo' => [
                'required',
                'string',
                'max:150',
            ],

            'usuario' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('usuarios', 'usuario'),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'estado' => [
                'required',
                Rule::in(['Activo', 'Inactivo']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'rol_id.required' => 'Selecciona un rol.',
            'rol_id.exists' => 'El rol seleccionado no es válido.',

            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'nombre_completo.max' => 'El nombre no puede exceder 150 caracteres.',

            'usuario.required' => 'El nombre de usuario es obligatorio.',
            'usuario.max' => 'El usuario no puede exceder 50 caracteres.',
            'usuario.alpha_dash' => 'El usuario solo puede contener letras, números, guiones y guiones bajos.',
            'usuario.unique' => 'Ese nombre de usuario ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',

            'estado.required' => 'Selecciona el estado del usuario.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ];
    }
}