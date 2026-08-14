<?php

namespace Tests\Feature;

use App\Enums\EstadoCategoria;
use App\Enums\EstadoProducto;
use App\Models\Categoria;
use App\Models\Rol;
use App\Models\Usuario;
use App\Services\ProductoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    private function crearAdministrador(): Usuario
    {
        $rol = Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Rol administrador de prueba',
        ]);

        return Usuario::create([
            'rol_id' => $rol->id,
            'creado_por_id' => null,
            'nombre_completo' => 'Administrador Prueba',
            'usuario' => 'adminproducto',
            'password' => Hash::make('Password123'),
            'estado' => 'Activo',
        ]);
    }

    private function crearCategoria(
        string $estado = 'Activa'
    ): Categoria {
        return Categoria::create([
            'nombre' => 'Categoría Prueba',
            'descripcion' => 'Categoría para pruebas',
            'estado' => $estado,
        ]);
    }

    public function test_service_crea_producto_con_inventario_y_movimiento_inicial(): void
    {
        $usuario = $this->crearAdministrador();
        $categoria = $this->crearCategoria();

        $service = app(
            ProductoService::class
        );

        $producto = $service->crear([
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto Prueba',
            'descripcion' => 'Producto automático',
            'precio_compra' => 20,
            'precio_venta' => 30,
            'stock_inicial' => 8,
            'estado' => EstadoProducto::ACTIVO,
        ], $usuario);

        $this->assertDatabaseHas(
            'productos',
            [
                'id' => $producto->id,
                'nombre' => 'Producto Prueba',
                'codigo' => 'PRO-000001',
                'stock_inicial' => 8,
                'estado' => 'Activo',
            ]
        );

        $this->assertDatabaseHas(
            'inventarios',
            [
                'producto_id' => $producto->id,
                'stock_actual' => 8,
                'stock_minimo' => 10,
                'estado' => 'Stock bajo',
            ]
        );

        $inventario = $producto
            ->inventario()
            ->firstOrFail();

        $this->assertDatabaseHas(
            'movimientos_inventario',
            [
                'inventario_id' => $inventario->id,
                'usuario_id' => $usuario->id,
                'tipo_movimiento' => 'Entrada',
                'cantidad' => 8,
                'stock_anterior' => 0,
                'stock_resultante' => 8,
                'motivo' => 'Stock inicial del producto',
            ]
        );
    }

    public function test_codigo_se_genera_automaticamente_en_secuencia(): void
    {
        $usuario = $this->crearAdministrador();
        $categoria = $this->crearCategoria();

        $service = app(
            ProductoService::class
        );

        $producto1 = $service->crear([
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto Uno',
            'precio_compra' => 10,
            'precio_venta' => 15,
            'stock_inicial' => 1,
            'estado' => EstadoProducto::ACTIVO,
        ], $usuario);

        $producto2 = $service->crear([
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto Dos',
            'precio_compra' => 20,
            'precio_venta' => 25,
            'stock_inicial' => 2,
            'estado' => EstadoProducto::ACTIVO,
        ], $usuario);

        $this->assertSame(
            'PRO-000001',
            $producto1->codigo
        );

        $this->assertSame(
            'PRO-000002',
            $producto2->codigo
        );
    }

    public function test_stock_cero_crea_inventario_agotado(): void
    {
        $usuario = $this->crearAdministrador();
        $categoria = $this->crearCategoria();

        $service = app(
            ProductoService::class
        );

        $producto = $service->crear([
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto Sin Stock',
            'precio_compra' => 10,
            'precio_venta' => 15,
            'stock_inicial' => 0,
            'estado' => EstadoProducto::ACTIVO,
        ], $usuario);

        $this->assertDatabaseHas(
            'inventarios',
            [
                'producto_id' => $producto->id,
                'stock_actual' => 0,
                'estado' => 'Agotado',
            ]
        );
    }
}
