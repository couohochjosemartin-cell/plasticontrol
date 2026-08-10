<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConfiguracionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador() ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre_negocio' => [
                'required',
                'string',
                'max:150',
            ],

            'propietario' => [
                'required',
                'string',
                'max:150',
            ],

            'rfc' => [
                'nullable',
                'string',
                'max:20',
            ],

            'telefono' => [
                'nullable',
                'string',
                'max:20',
            ],

            'direccion' => [
                'nullable',
                'string',
                'max:255',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'eliminar_logo' => [
                'nullable',
                'boolean',
            ],

            'moneda' => [
                'required',
                Rule::in(['MXN']),
            ],

            'iva' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'zona_horaria' => [
                'required',
                'string',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_negocio.required' =>
                'El nombre del negocio es obligatorio.',

            'nombre_negocio.max' =>
                'El nombre del negocio no puede exceder 150 caracteres.',

            'propietario.required' =>
                'El nombre del propietario es obligatorio.',

            'propietario.max' =>
                'El propietario no puede exceder 150 caracteres.',

            'rfc.max' =>
                'El RFC no puede exceder 20 caracteres.',

            'telefono.max' =>
                'El teléfono no puede exceder 20 caracteres.',

            'direccion.max' =>
                'La dirección no puede exceder 255 caracteres.',

            'logo.image' =>
                'El archivo seleccionado debe ser una imagen.',

            'logo.mimes' =>
                'El logo debe ser JPG, JPEG, PNG o WEBP.',

            'logo.max' =>
                'El logo no puede superar 2 MB.',

            'moneda.required' =>
                'Selecciona la moneda del sistema.',

            'moneda.in' =>
                'La moneda seleccionada no está disponible.',

            'iva.required' =>
                'El IVA es obligatorio.',

            'iva.numeric' =>
                'El IVA debe ser un valor numérico.',

            'iva.min' =>
                'El IVA no puede ser negativo.',

            'iva.max' =>
                'El IVA no puede ser mayor al 100%.',

            'zona_horaria.required' =>
                'La zona horaria es obligatoria.',
        ];
    }
}
