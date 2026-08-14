<?php

namespace Tests\Feature;

use App\Enums\EstadoInventario;
use App\Enums\EstadoProducto;
use App\Enums\EstadoVenta;
use App\Models\Categoria;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Rol;
use App\Models\Usuario;
use App\Services\VentaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Tests\TestCase;

class VentaTest extends TestCase
{
    use RefreshDatabase;

    private function crearUsuario(): Usuario
    {
        $rol = Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Rol de prueba',
        ]);

        return Usuario::create([
            'rol_id' => $rol->id,
            'creado_por_id' => null,
            'nombre_completo' => 'Administrador Ventas',
            'usuario' => 'adminventas',
            'password' => Hash::make('Password123'),
            'estado' => 'Activo',
        ]);
    }

    private function crearProducto(
        int $stock = 10,
        float $precio = 30
    ): Producto {
        $categoria = Categoria::create([
            'nombre' => 'Categoría Ventas',
            'descripcion' => 'Categoría para pruebas',
            'estado' => 'Activa',
        ]);

        $producto = Producto::create([
            'categoria_id' => $categoria->id,
            'codigo' => 'VEN-000001',
            'nombre' => 'Producto Venta',
            'descripcion' => 'Producto para pruebas',
            'precio_compra' => 20,
            'precio_venta' => $precio,
            'stock_inicial' => $stock,
            'estado' => EstadoProducto::ACTIVO,
        ]);

        Inventario::create([
            'producto_id' => $producto->id,
            'stock_actual' => $stock,
            'stock_minimo' => 5,
            'estado' => $stock === 0
                ? EstadoInventario::AGOTADO
                : (
                    $stock <= 5
                        ? EstadoInventario::STOCK_BAJO
                        : EstadoInventario::DISPONIBLE
                ),
        ]);

        return $producto;
    }

    public function test_venta_valida_crea_todo_y_descuenta_inventario(): void
    {
        $usuario = $this->crearUsuario();

        $producto = $this->crearProducto(
            10,
            30
        );

        $service = app(
            VentaService::class
        );

        $venta = $service->registrar(
            [
                'productos' => [
                    [
                        'id' => $producto->id,
                        'cantidad' => 2,
                    ],
                ],
                'descuento' => 5,
                'pago_recibido' => 100,
            ],
            $usuario
        );

        /*
        |--------------------------------------------------------------
        | 2 productos x $30 = $60
        | descuento = $5
        | total = $55
        | pago = $100
        | cambio = $45
        |--------------------------------------------------------------
        */

        $this->assertSame(
            '60.00',
            $venta->subtotal
        );

        $this->assertSame(
            '5.00',
            $venta->descuento
        );

        $this->assertSame(
            '55.00',
            $venta->total
        );

        $this->assertSame(
            '45.00',
            $venta->cambio
        );

        $this->assertSame(
            EstadoVenta::COMPLETADA,
            $venta->estado
        );

        $this->assertDatabaseHas(
            'ventas',
            [
                'id' => $venta->id,
                'usuario_id' => $usuario->id,
                'subtotal' => 60,
                'descuento' => 5,
                'total' => 55,
                'pago_recibido' => 100,
                'cambio' => 45,
                'estado' => 'Completada',
            ]
        );

        $this->assertDatabaseHas(
            'detalles_venta',
            [
                'venta_id' => $venta->id,
                'producto_id' => $producto->id,
                'cantidad' => 2,
                'precio_unitario' => 30,
                'subtotal' => 60,
            ]
        );

        $this->assertDatabaseHas(
            'inventarios',
            [
                'producto_id' => $producto->id,
                'stock_actual' => 8,
                'estado' => 'Disponible',
            ]
        );

        $this->assertDatabaseHas(
            'movimientos_inventario',
            [
                'usuario_id' => $usuario->id,
                'tipo_movimiento' => 'Salida',
                'cantidad' => 2,
                'stock_anterior' => 10,
                'stock_resultante' => 8,
                'referencia_tipo' => 'Venta',
                'referencia_id' => $venta->id,
            ]
        );

        $this->assertDatabaseCount(
            'ventas',
            1
        );

        $this->assertDatabaseCount(
            'detalles_venta',
            1
        );

        $this->assertDatabaseCount(
            'movimientos_inventario',
            1
        );
    }

    public function test_stock_insuficiente_rechaza_venta_sin_modificar_datos(): void
    {
        $usuario = $this->crearUsuario();

        $producto = $this->crearProducto(
            3,
            30
        );

        $service = app(
            VentaService::class
        );

        try {
            $service->registrar(
                [
                    'productos' => [
                        [
                            'id' => $producto->id,
                            'cantidad' => 10,
                        ],
                    ],
                    'descuento' => 0,
                    'pago_recibido' => 500,
                ],
                $usuario
            );

            $this->fail(
                'Se esperaba una ValidationException.'
            );
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey(
                'productos',
                $exception->errors()
            );
        }

        $this->assertDatabaseCount(
            'ventas',
            0
        );

        $this->assertDatabaseCount(
            'detalles_venta',
            0
        );

        $this->assertDatabaseCount(
            'movimientos_inventario',
            0
        );

        $this->assertDatabaseHas(
            'inventarios',
            [
                'producto_id' => $producto->id,
                'stock_actual' => 3,
            ]
        );
    }

    public function test_pago_insuficiente_rechaza_venta_sin_modificar_inventario(): void
    {
        $usuario = $this->crearUsuario();

        $producto = $this->crearProducto(
            10,
            30
        );

        $service = app(
            VentaService::class
        );

        try {
            $service->registrar(
                [
                    'productos' => [
                        [
                            'id' => $producto->id,
                            'cantidad' => 2,
                        ],
                    ],
                    'descuento' => 0,

                    // Total = $60
                    'pago_recibido' => 20,
                ],
                $usuario
            );

            $this->fail(
                'Se esperaba una ValidationException.'
            );
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey(
                'pago_recibido',
                $exception->errors()
            );
        }

        $this->assertDatabaseCount(
            'ventas',
            0
        );

        $this->assertDatabaseCount(
            'detalles_venta',
            0
        );

        $this->assertDatabaseCount(
            'movimientos_inventario',
            0
        );

        $this->assertDatabaseHas(
            'inventarios',
            [
                'producto_id' => $producto->id,
                'stock_actual' => 10,
            ]
        );
    }

    public function test_falla_durante_el_descuento_hace_rollback_total(): void
    {
        $usuario = $this->crearUsuario();

        $producto = $this->crearProducto(
            10,
            30
        );

        /*
        |--------------------------------------------------------------
        | Forzamos una falla justo cuando VentaService intenta
        | actualizar Inventario.
        |
        | Para ese momento Venta y Detalle ya fueron creados dentro
        | de la transacción. Si DB::transaction funciona correctamente,
        | ambos deberán desaparecer mediante rollback.
        |--------------------------------------------------------------
        */

        $evento = 'eloquent.updating: '
            . Inventario::class;

        Event::listen(
            $evento,
            function (): void {
                throw new RuntimeException(
                    'Falla forzada durante actualización de inventario.'
                );
            }
        );

        $service = app(
            VentaService::class
        );

        try {
            $service->registrar(
                [
                    'productos' => [
                        [
                            'id' => $producto->id,
                            'cantidad' => 2,
                        ],
                    ],
                    'descuento' => 0,
                    'pago_recibido' => 100,
                ],
                $usuario
            );

            $this->fail(
                'Se esperaba una RuntimeException.'
            );
        } catch (RuntimeException $exception) {
            $this->assertSame(
                'Falla forzada durante actualización de inventario.',
                $exception->getMessage()
            );
        } finally {
            Event::forget(
                $evento
            );
        }

        /*
        |--------------------------------------------------------------
        | Si hubo rollback real:
        | - Venta NO existe
        | - Detalle NO existe
        | - Stock sigue en 10
        | - Movimiento NO existe
        |--------------------------------------------------------------
        */

        $this->assertDatabaseCount(
            'ventas',
            0
        );

        $this->assertDatabaseCount(
            'detalles_venta',
            0
        );

        $this->assertDatabaseCount(
            'movimientos_inventario',
            0
        );

        $this->assertDatabaseHas(
            'inventarios',
            [
                'producto_id' => $producto->id,
                'stock_actual' => 10,
            ]
        );
    }
}