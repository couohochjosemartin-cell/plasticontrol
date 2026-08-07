<?php

namespace App\Services;

use App\Models\Inventario;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventarioService
{
    public function registrarMovimiento(
        Inventario $inventario,
        array $datos,
        Usuario $usuario
    ): Inventario {
        return DB::transaction(
            function () use (
                $inventario,
                $datos,
                $usuario
            ): Inventario {
                $inventario = Inventario::query()
                    ->lockForUpdate()
                    ->findOrFail($inventario->id);

                $stockAnterior = $inventario->stock_actual;
                $cantidad = (int) $datos['cantidad'];

                if ($datos['tipo_movimiento'] === 'Entrada') {
                    $stockResultante = $stockAnterior + $cantidad;
                } else {
                    if ($cantidad > $stockAnterior) {
                        throw ValidationException::withMessages([
                            'cantidad' => 'No hay existencias suficientes para realizar esta salida.',
                        ]);
                    }

                    $stockResultante = $stockAnterior - $cantidad;
                }

                $inventario->update([
                    'stock_actual' => $stockResultante,
                    'estado' => $this->estadoInventario(
                        $stockResultante,
                        $inventario->stock_minimo
                    ),
                ]);

                $inventario->movimientos()->create([
                    'usuario_id' => $usuario->id,
                    'tipo_movimiento' => $datos['tipo_movimiento'],
                    'cantidad' => $cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_resultante' => $stockResultante,
                    'motivo' => $datos['motivo'],
                    'referencia_tipo' => 'Ajuste manual',
                    'referencia_id' => null,
                    'fecha_movimiento' => now(),
                ]);

                return $inventario->refresh();
            }
        );
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