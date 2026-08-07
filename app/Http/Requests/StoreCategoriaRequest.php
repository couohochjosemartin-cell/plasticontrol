<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador() ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categorias', 'nombre'),
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
            'estado' => [
                'required',
                Rule::in(['Activa', 'Inactiva']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
            'descripcion.max' => 'La descripción no puede exceder 255 caracteres.',
            'estado.required' => 'Debes seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ];
    }
}
