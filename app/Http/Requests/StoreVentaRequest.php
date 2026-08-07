<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador()
            || $this->user()?->esCajero();
    }

    public function rules(): array
    {
        return [
            'productos' => [
                'required',
                'array',
                'min:1',
            ],

            'productos.*.id' => [
                'required',
                'integer',
                'distinct',
                'exists:productos,id',
            ],

            'productos.*.cantidad' => [
                'required',
                'integer',
                'min:1',
                'max:999999',
            ],

            'descuento' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'pago_recibido' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'productos.required' => 'Agrega al menos un producto a la venta.',
            'productos.array' => 'Los productos de la venta no son válidos.',
            'productos.min' => 'Agrega al menos un producto a la venta.',

            'productos.*.id.required' => 'Existe un producto inválido en la venta.',
            'productos.*.id.exists' => 'Uno de los productos ya no existe.',
            'productos.*.id.distinct' => 'Un producto aparece repetido en la venta.',

            'productos.*.cantidad.required' => 'Indica la cantidad del producto.',
            'productos.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'productos.*.cantidad.min' => 'La cantidad debe ser mayor a cero.',

            'descuento.numeric' => 'El descuento debe ser numérico.',
            'descuento.min' => 'El descuento no puede ser negativo.',

            'pago_recibido.required' => 'Indica el efectivo recibido.',
            'pago_recibido.numeric' => 'El efectivo recibido debe ser numérico.',
            'pago_recibido.min' => 'El efectivo recibido no puede ser negativo.',
        ];
    }
}