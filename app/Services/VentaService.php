<?php

namespace App\Services;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VentaService
{
    public function registrar(
        array $datos,
        Usuario $usuario
    ): Venta {
        return DB::transaction(
            function () use ($datos, $usuario): Venta {
                $subtotal = 0;
                $lineas = [];

                foreach ($datos['productos'] as $item) {
                    $producto = Producto::query()
                        ->where('estado', 'Activo')
                        ->whereNull('deleted_at')
                        ->find($item['id']);

                    if (! $producto) {
                        throw ValidationException::withMessages([
                            'productos' => 'Uno de los productos ya no está disponible para venta.',
                        ]);
                    }

                    $inventario = Inventario::query()
                        ->lockForUpdate()
                        ->where('producto_id', $producto->id)
                        ->firstOrFail();

                    $cantidad = (int) $item['cantidad'];

                    if ($cantidad > $inventario->stock_actual) {
                        throw ValidationException::withMessages([
                            'productos' => "Stock insuficiente para {$producto->nombre}. Disponible: {$inventario->stock_actual}.",
                        ]);
                    }

                    $precioUnitario = (float) $producto->precio_venta;
                    $subtotalLinea = $precioUnitario * $cantidad;

                    $subtotal += $subtotalLinea;

                    $lineas[] = [
                        'producto' => $producto,
                        'inventario' => $inventario,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precioUnitario,
                        'subtotal' => $subtotalLinea,
                    ];
                }

                $descuento = (float) ($datos['descuento'] ?? 0);

                if ($descuento > $subtotal) {
                    throw ValidationException::withMessages([
                        'descuento' => 'El descuento no puede ser mayor al subtotal de la venta.',
                    ]);
                }

                $total = $subtotal - $descuento;
                $pagoRecibido = (float) $datos['pago_recibido'];

                if ($pagoRecibido < $total) {
                    throw ValidationException::withMessages([
                        'pago_recibido' => 'El efectivo recibido es insuficiente para completar la venta.',
                    ]);
                }

                $cambio = $pagoRecibido - $total;

                $venta = Venta::create([
                    'folio' => $this->generarFolio(),
                    'usuario_id' => $usuario->id,
                    'metodo_pago' => 'Efectivo',
                    'subtotal' => $subtotal,
                    'descuento' => $descuento,
                    'total' => $total,
                    'pago_recibido' => $pagoRecibido,
                    'cambio' => $cambio,
                    'estado' => 'Completada',
                    'fecha_venta' => now(),
                ]);

                foreach ($lineas as $linea) {
                    $venta->detalles()->create([
                        'producto_id' => $linea['producto']->id,
                        'cantidad' => $linea['cantidad'],
                        'precio_unitario' => $linea['precio_unitario'],
                        'subtotal' => $linea['subtotal'],
                    ]);

                    $inventario = $linea['inventario'];

                    $stockAnterior = $inventario->stock_actual;

                    $stockResultante = $stockAnterior
                        - $linea['cantidad'];

                    $inventario->update([
                        'stock_actual' => $stockResultante,
                        'estado' => $this->estadoInventario(
                            $stockResultante,
                            $inventario->stock_minimo
                        ),
                    ]);

                    $inventario->movimientos()->create([
                        'usuario_id' => $usuario->id,
                        'tipo_movimiento' => 'Salida',
                        'cantidad' => $linea['cantidad'],
                        'stock_anterior' => $stockAnterior,
                        'stock_resultante' => $stockResultante,
                        'motivo' => 'Venta ' . $venta->folio,
                        'referencia_tipo' => 'Venta',
                        'referencia_id' => $venta->id,
                        'fecha_movimiento' => now(),
                    ]);
                }

                return $venta->load([
                    'usuario',
                    'detalles.producto',
                ]);
            }
        );
    }

    private function generarFolio(): string
    {
        do {
            $folio = 'V-'
                . now()->format('ymd-His')
                . '-'
                . random_int(1000, 9999);
        } while (
            Venta::where('folio', $folio)->exists()
        );

        return $folio;
    }

    private function estadoInventario(
        int $stockActual,
        int $stockMinimo
    ): string {
        if ($stockActual === 0) {
            return 'Agotado';
        }

        if ($stockActual <= $stockMinimo) {
            return 'Stock bajo';
        }

        return 'Disponible';
    }
}