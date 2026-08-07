<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductoService
{
    public function crear(
        array $datos,
        Usuario $usuario
    ): Producto {
        return DB::transaction(
            function () use ($datos, $usuario): Producto {
                $imagen = $datos['imagen'] ?? null;

                unset($datos['imagen']);

                if ($imagen instanceof UploadedFile) {
                    $datos['imagen'] = $imagen->store(
                        'productos',
                        'public'
                    );
                }

                // El código se genera automáticamente.
                $datos['codigo'] = $this->generarCodigo();

                $producto = Producto::create($datos);

                $inventario = $producto->inventario()->create([
                    'stock_actual' => $producto->stock_inicial,
                    'stock_minimo' => 10,
                    'estado' => $this->estadoInventario(
                        $producto->stock_inicial,
                        10
                    ),
                ]);

                $inventario->movimientos()->create([
                    'usuario_id' => $usuario->id,
                    'tipo_movimiento' => 'Entrada',
                    'cantidad' => $producto->stock_inicial,
                    'stock_anterior' => 0,
                    'stock_resultante' => $producto->stock_inicial,
                    'motivo' => 'Stock inicial del producto',
                    'referencia_tipo' => 'Producto',
                    'referencia_id' => $producto->id,
                    'fecha_movimiento' => now(),
                ]);

                return $producto;
            }
        );
    }

    public function actualizar(
        Producto $producto,
        array $datos
    ): Producto {
        return DB::transaction(
            function () use ($producto, $datos): Producto {
                $imagen = $datos['imagen'] ?? null;

                $eliminarImagen = (bool) (
                    $datos['eliminar_imagen'] ?? false
                );

                unset(
                    $datos['imagen'],
                    $datos['eliminar_imagen'],
                    $datos['stock_inicial'],
                    $datos['codigo']
                );

                if ($eliminarImagen && $producto->imagen) {
                    Storage::disk('public')->delete(
                        $producto->imagen
                    );

                    $datos['imagen'] = null;
                }

                if ($imagen instanceof UploadedFile) {
                    if ($producto->imagen) {
                        Storage::disk('public')->delete(
                            $producto->imagen
                        );
                    }

                    $datos['imagen'] = $imagen->store(
                        'productos',
                        'public'
                    );
                }

                $producto->update($datos);

                return $producto->refresh();
            }
        );
    }

    private function generarCodigo(): string
    {
        $ultimoId = Producto::withTrashed()->max('id') ?? 0;

        $siguiente = $ultimoId + 1;

        return 'PRO-' . str_pad(
            (string) $siguiente,
            6,
            '0',
            STR_PAD_LEFT
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