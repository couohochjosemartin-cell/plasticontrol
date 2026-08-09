<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador() ?? false;
    }

    public function rules(): array
    {
        $usuario = $this->route('usuario');

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
                Rule::unique('usuarios', 'usuario')
                    ->ignore($usuario),
            ],

            'password' => [
                'nullable',
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
        return (new StoreUsuarioRequest())->messages();
    }
}