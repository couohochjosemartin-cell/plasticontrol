<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

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


    // =========================
    // DASHBOARD
    // =========================

    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');


    // =========================
    // CERRAR SESIÓN
    // =========================

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');
});

Route::redirect('/', '/login');