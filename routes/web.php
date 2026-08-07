<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::resource(
        'categorias',
        CategoriaController::class
    );

    Route::patch(
        '/categorias/{categoria}/restaurar',
        [CategoriaController::class, 'restore']
    )->name('categorias.restore');

    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');
});

Route::redirect('/', '/login');