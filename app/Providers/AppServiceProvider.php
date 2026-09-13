<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\Inventario;
use App\Policies\InventarioPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL; // <-- 1. Agrega esta línea arriba


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
           Gate::policy(
             Inventario::class,
               InventarioPolicy::class
        );
        Paginator::useBootstrapFive();
    }
}