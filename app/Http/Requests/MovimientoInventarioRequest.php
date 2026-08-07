<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovimientoInventarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador()
            || $this->user()?->esInventario();
    }

    public function rules(): array
    {
        return [
            'tipo_movimiento' => [
                'required',
                Rule::in(['Entrada', 'Salida']),
            ],

            'cantidad' => [
                'required',
                'integer',
                'min:1',
                'max:999999999',
            ],

            'motivo' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_movimiento.required' => 'Selecciona el tipo de movimiento.',
            'tipo_movimiento.in' => 'El tipo de movimiento no es válido.',

            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser mayor a cero.',

            'motivo.required' => 'Debes indicar el motivo del movimiento.',
            'motivo.max' => 'El motivo no puede exceder 255 caracteres.',
        ];
    }
}
