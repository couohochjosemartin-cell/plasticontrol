<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ReporteController;


Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});

Route::middleware('auth')->group(function (): void {

    // =========================
    // RUTAS DE CATEGORÍAS
    // =========================

    Route::resource(
        'categorias',
        CategoriaController::class
    );

    Route::patch(
        '/categorias/{categoria}/restaurar',
        [CategoriaController::class, 'restore']
    )->name('categorias.restore');


// =========================
// PUNTO DE VENTA
// =========================

Route::get(
    '/ventas',
    [VentaController::class, 'index']
)->name('ventas.index');

Route::get(
    '/punto-venta',
    [VentaController::class, 'create']
)->name('ventas.create');

Route::post(
    '/punto-venta',
    [VentaController::class, 'store']
)->name('ventas.store');

Route::get(
    '/ventas/{venta}',
    [VentaController::class, 'show']
)->name('ventas.show');

    // =========================
    // RUTAS DE PRODUCTOS
    // AQUÍ DEBES PEGARLAS
    // =========================

    Route::resource(
        'productos',
        ProductoController::class
    );

    Route::patch(
        '/productos/{producto}/restaurar',
        [ProductoController::class, 'restore']
    )->name('productos.restore');


Route::get(
    '/inventario',
    [InventarioController::class, 'index']
)->name('inventario.index');

Route::get(
    '/inventario/{inventario}',
    [InventarioController::class, 'show']
)->name('inventario.show');

Route::post(
    '/inventario/{inventario}/movimiento',
    [InventarioController::class, 'movimiento']
)->name('inventario.movimiento');


Route::resource(
    'usuarios',
    UsuarioController::class
);

Route::patch(
    '/usuarios/{usuario}/restaurar',
    [UsuarioController::class, 'restore']
)->name('usuarios.restore');


Route::get(
    '/configuracion',
    [ConfiguracionController::class, 'edit']
)->name('configuracion.edit');

Route::put(
    '/configuracion',
    [ConfiguracionController::class, 'update']
)->name('configuracion.update');

    // =========================
    // DASHBOARD
    // =========================

    Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)->name('dashboard');


Route::get(
    '/reportes',
    [ReporteController::class, 'index']
)->name('reportes.index');

    // =========================
    // CERRAR SESIÓN
    // =========================

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');
});

Route::redirect('/', '/login');