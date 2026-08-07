<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\Inventario;
use App\Policies\InventarioPolicy;
use Illuminate\Support\Facades\Gate;


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