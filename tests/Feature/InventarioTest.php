<?php

namespace Tests\Feature;

use App\Enums\EstadoInventario;
use App\Models\Categoria;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Rol;
use App\Models\Usuario;
use App\Services\InventarioService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class InventarioTest extends TestCase
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
            'nombre_completo' => 'Administrador Inventario',
            'usuario' => 'admininventario',
            'password' => Hash::make('Password123'),
            'estado' => 'Activo',
        ]);
    }

    private function crearInventario(
        int $stockActual = 10,
        int $stockMinimo = 5
    ): Inventario {
        $categoria = Categoria::create([
            'nombre' => 'Categoría Inventario',
            'descripcion' => 'Prueba',
            'estado' => 'Activa',
        ]);

        $producto = Producto::create([
            'categoria_id' => $categoria->id,
            'codigo' => 'INV-000001',
            'nombre' => 'Producto Inventario',
            'descripcion' => 'Prueba',
            'precio_compra' => 10,
            'precio_venta' => 20,
            'stock_inicial' => $stockActual,
            'estado' => 'Activo',
        ]);

        return Inventario::create([
            'producto_id' => $producto->id,
            'stock_actual' => $stockActual,
            'stock_minimo' => $stockMinimo,
            'estado' => $stockActual === 0
                ? EstadoInventario::AGOTADO
                : (
                    $stockActual <= $stockMinimo
                        ? EstadoInventario::STOCK_BAJO
                        : EstadoInventario::DISPONIBLE
                ),
        ]);
    }

    public function test_entrada_aumenta_stock_y_guarda_movimiento(): void
    {
        $usuario = $this->crearUsuario();
        $inventario = $this->crearInventario(
            10,
            5
        );

        $service = app(
            InventarioService::class
        );

        $resultado = $service->registrarMovimiento(
            $inventario,
            [
                'tipo_movimiento' => 'Entrada',
                'cantidad' => 5,
                'motivo' => 'Entrada de prueba',
            ],
            $usuario
        );

        $this->assertSame(
            15,
            $resultado->stock_actual
        );

        $this->assertSame(
            EstadoInventario::DISPONIBLE,
            $resultado->estado
        );

        $this->assertDatabaseHas(
            'movimientos_inventario',
            [
                'inventario_id' => $inventario->id,
                'usuario_id' => $usuario->id,
                'tipo_movimiento' => 'Entrada',
                'cantidad' => 5,
                'stock_anterior' => 10,
                'stock_resultante' => 15,
                'motivo' => 'Entrada de prueba',
            ]
        );
    }

    public function test_salida_valida_disminuye_stock_y_actualiza_estado(): void
    {
        $usuario = $this->crearUsuario();
        $inventario = $this->crearInventario(
            12,
            5
        );

        $service = app(
            InventarioService::class
        );

        $resultado = $service->registrarMovimiento(
            $inventario,
            [
                'tipo_movimiento' => 'Salida',
                'cantidad' => 8,
                'motivo' => 'Salida de prueba',
            ],
            $usuario
        );

        $this->assertSame(
            4,
            $resultado->stock_actual
        );

        $this->assertSame(
            EstadoInventario::STOCK_BAJO,
            $resultado->estado
        );

        $this->assertDatabaseHas(
            'movimientos_inventario',
            [
                'inventario_id' => $inventario->id,
                'cantidad' => 8,
                'stock_anterior' => 12,
                'stock_resultante' => 4,
            ]
        );
    }

    public function test_salida_que_deja_stock_cero_marca_agotado(): void
    {
        $usuario = $this->crearUsuario();
        $inventario = $this->crearInventario(
            5,
            5
        );

        $service = app(
            InventarioService::class
        );

        $resultado = $service->registrarMovimiento(
            $inventario,
            [
                'tipo_movimiento' => 'Salida',
                'cantidad' => 5,
                'motivo' => 'Agotar inventario',
            ],
            $usuario
        );

        $this->assertSame(
            0,
            $resultado->stock_actual
        );

        $this->assertSame(
            EstadoInventario::AGOTADO,
            $resultado->estado
        );
    }

    public function test_salida_excesiva_se_rechaza_y_no_modifica_stock(): void
    {
        $usuario = $this->crearUsuario();
        $inventario = $this->crearInventario(
            5,
            5
        );

        $service = app(
            InventarioService::class
        );

        try {
            $service->registrarMovimiento(
                $inventario,
                [
                    'tipo_movimiento' => 'Salida',
                    'cantidad' => 10,
                    'motivo' => 'Salida imposible',
                ],
                $usuario
            );

            $this->fail(
                'Se esperaba una ValidationException.'
            );
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey(
                'cantidad',
                $exception->errors()
            );
        }

        $inventario->refresh();

        $this->assertSame(
            5,
            $inventario->stock_actual
        );

        $this->assertDatabaseCount(
            'movimientos_inventario',
            0
        );
    }
}