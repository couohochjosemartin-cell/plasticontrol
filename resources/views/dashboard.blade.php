@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Resumen general de PlastiControl')

@section('content')
    <div class="row g-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="dashboard-card">
                <div class="dashboard-card__icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>

                <div class="dashboard-card__label">
                    Ventas del día
                </div>

                <p class="dashboard-card__value">
                    $0.00
                </p>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="dashboard-card">
                <div class="dashboard-card__icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>

                <div class="dashboard-card__label">
                    Ganancia del día
                </div>

                <p class="dashboard-card__value">
                    $0.00
                </p>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="dashboard-card">
                <div class="dashboard-card__icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>

                <div class="dashboard-card__label">
                    Productos con stock bajo
                </div>

                <p class="dashboard-card__value">
                    0
                </p>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="dashboard-card">
                <div class="dashboard-card__icon">
                    <i class="bi bi-trophy"></i>
                </div>

                <div class="dashboard-card__label">
                    Producto más vendido
                </div>

                <p class="dashboard-card__value fs-5">
                    Sin datos
                </p>
            </article>
        </div>

        <div class="col-12 col-xl-8">
            <section class="dashboard-panel">
                <h2 class="dashboard-panel__title">
                    Ventas de los últimos meses
                </h2>

                <div class="empty-state">
                    <i class="bi bi-bar-chart"></i>

                    <div>
                        La gráfica aparecerá cuando existan ventas registradas.
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="dashboard-panel">
                <h2 class="dashboard-panel__title">
                    Ventas recientes
                </h2>

                <div class="empty-state">
                    <i class="bi bi-receipt"></i>

                    <div>
                        Todavía no existen ventas.
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection