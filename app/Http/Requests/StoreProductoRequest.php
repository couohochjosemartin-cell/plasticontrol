<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador() ?? false;
    }

    public function rules(): array
    {
        return [
            'categoria_id' => [
                'required',
                'integer',
                Rule::exists('categorias', 'id')
                    ->where(
                        fn ($query) => $query
                            ->where('estado', 'Activa')
                            ->whereNull('deleted_at')
                    ),
            ],

           
            'nombre' => [
                'required',
                'string',
                'max:150',
            ],

            'descripcion' => [
                'nullable',
                'string',
            ],

            'precio_compra' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'precio_venta' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'gte:precio_compra',
            ],

            'stock_inicial' => [
                'required',
                'integer',
                'min:0',
                'max:999999999',
            ],

            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
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
            'categoria_id.required' => 'Selecciona una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no está disponible.',

           
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder 150 caracteres.',

            'precio_compra.required' => 'El precio de compra es obligatorio.',
            'precio_compra.numeric' => 'El precio de compra debe ser numérico.',
            'precio_compra.min' => 'El precio de compra no puede ser negativo.',

            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'precio_venta.numeric' => 'El precio de venta debe ser numérico.',
            'precio_venta.min' => 'El precio de venta no puede ser negativo.',
            'precio_venta.gte' => 'El precio de venta no puede ser menor al precio de compra.',

            'stock_inicial.required' => 'El stock inicial es obligatorio.',
            'stock_inicial.integer' => 'El stock inicial debe ser un número entero.',
            'stock_inicial.min' => 'El stock inicial no puede ser negativo.',

            'imagen.image' => 'El archivo seleccionado debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
            'imagen.max' => 'La imagen no puede superar 2 MB.',

            'estado.required' => 'Selecciona el estado del producto.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ];
    }
}