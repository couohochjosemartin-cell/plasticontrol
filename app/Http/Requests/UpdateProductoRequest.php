<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador() ?? false;
    }

    public function rules(): array
    {
        $producto = $this->route('producto');

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

            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'eliminar_imagen' => [
                'nullable',
                'boolean',
            ],

            'estado' => [
                'required',
                Rule::in(['Activo', 'Inactivo']),
            ],
        ];
    }

    public function messages(): array
    {
        return (new StoreProductoRequest())->messages();
    }
}