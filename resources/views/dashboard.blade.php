@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Resumen general de PlastiControl')

@section('content')
    <div class="row g-4">

        {{-- =========================
             VENTAS DEL DÍA
        ========================== --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="dashboard-card">
                <div class="dashboard-card__icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>

                <div class="dashboard-card__label">
                    Ventas del día
                </div>

                <p class="dashboard-card__value">
                    ${{ number_format(
                        (float) $ventasHoy,
                        2
                    ) }}
                </p>
            </article>
        </div>

        {{-- =========================
             GANANCIA DEL DÍA
        ========================== --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="dashboard-card">
                <div class="dashboard-card__icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>

                <div class="dashboard-card__label">
                    Ganancia del día
                </div>

                <p class="dashboard-card__value">
                    ${{ number_format(
                        (float) $gananciaHoy,
                        2
                    ) }}
                </p>
            </article>
        </div>

        {{-- =========================
             STOCK BAJO
        ========================== --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="dashboard-card">
                <div class="dashboard-card__icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>

                <div class="dashboard-card__label">
                    Productos con stock bajo
                </div>

                <p class="dashboard-card__value">
                    {{ $productosStockBajo }}
                </p>
            </article>
        </div>

        {{-- =========================
             PRODUCTO MÁS VENDIDO
        ========================== --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="dashboard-card">
                <div class="dashboard-card__icon">
                    <i class="bi bi-trophy"></i>
                </div>

                <div class="dashboard-card__label">
                    Producto más vendido
                </div>

                @if (
                    $productoMasVendido
                    && $productoMasVendido->producto
                )
                    <p class="dashboard-card__value fs-5 mb-1">
                        {{ $productoMasVendido->producto->nombre }}
                    </p>

                    <small class="text-muted">
                        {{ $productoMasVendido->total_vendido }}
                        unidades vendidas
                    </small>
                @else
                    <p class="dashboard-card__value fs-5">
                        Sin datos
                    </p>
                @endif
            </article>
        </div>

        {{-- =========================
             GRÁFICA DE VENTAS
        ========================== --}}
        <div class="col-12 col-xl-8">
            <section class="dashboard-panel">
                <div
                    class="d-flex justify-content-between align-items-center mb-4"
                >
                    <h2 class="dashboard-panel__title mb-0">
                        Ventas de los últimos 6 meses
                    </h2>

                    <i class="bi bi-bar-chart text-muted"></i>
                </div>

                @php
                    $maximoVentas = max(
                        1,
                        (float) $ventasPorMes->max('total')
                    );
                @endphp

                <div class="dashboard-chart">
                    @foreach ($ventasPorMes as $mes)
                        @php
                            $porcentaje = (
                                (float) $mes['total']
                                / $maximoVentas
                            ) * 100;
                        @endphp

                        <div class="dashboard-chart__column">
                            <div class="dashboard-chart__value">
                                @if ((float) $mes['total'] > 0)
                                    ${{ number_format(
                                        (float) $mes['total'],
                                        0
                                    ) }}
                                @endif
                            </div>

                            <div class="dashboard-chart__track">
                                <div
                                    class="dashboard-chart__bar"
                                    style="height: {{ max(
                                        4,
                                        $porcentaje
                                    ) }}%;"
                                    title="${{ number_format(
                                        (float) $mes['total'],
                                        2
                                    ) }}"
                                ></div>
                            </div>

                            <div class="dashboard-chart__label">
                                {{ $mes['mes'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

       {{-- =========================
     VENTAS RECIENTES
========================== --}}
<div class="col-12 col-xl-4">
    <section class="dashboard-panel">
        <div
            class="d-flex justify-content-between align-items-center mb-3"
        >
            <h2 class="dashboard-panel__title mb-0">
                Ventas recientes
            </h2>

            <a
                href="{{ route('ventas.index') }}"
                class="small text-decoration-none"
            >
                Ver todas
            </a>
        </div>

        @forelse ($ventasRecientes as $venta)
            <a
                href="{{ route('ventas.show', $venta) }}"
                class="recent-sale"
            >
                <div class="recent-sale__icon">
                    <i class="bi bi-receipt"></i>
                </div>

                <div class="recent-sale__information">
                    <strong>
                        {{ $venta->folio }}
                    </strong>

                    <small>
                        {{ $venta->fecha_venta->format(
                            'd/m/Y H:i'
                        ) }}
                    </small>
                </div>

                <div class="recent-sale__amount">
                    ${{ number_format(
                        (float) $venta->total,
                        2
                    ) }}
                </div>
            </a>
        @empty
            <div class="empty-state">
                <i class="bi bi-receipt"></i>

                <div>
                    Todavía no existen ventas.
                </div>
            </div>
        @endforelse
    </section>
</div>

{{-- =========================
     VENTAS DE LA SEMANA
========================== --}}
<div class="col-12">
    <section class="dashboard-panel">

        <div
            class="d-flex justify-content-between
                   align-items-center mb-4"
        >
            <div>
                <h2 class="dashboard-panel__title mb-1">
                    Ventas de la semana
                </h2>

                <small class="text-muted">
                    Ingresos diarios de lunes a domingo
                </small>
            </div>

            <i
                class="bi bi-calendar-week
                       text-muted fs-5"
            ></i>
        </div>

        @php
            $maximoSemana = max(
                1,
                (float) $ventasSemana->max('total')
            );

            $totalSemana = (float)
                $ventasSemana->sum('total');
        @endphp

        <div
            class="d-flex justify-content-between
                   align-items-center mb-3"
        >
            <span class="text-muted">
                Total semanal
            </span>

            <strong class="fs-5">
                ${{ number_format(
                    $totalSemana,
                    2
                ) }}
            </strong>
        </div>

        <div class="dashboard-chart">

            @foreach ($ventasSemana as $dia)

                @php
                    $porcentaje = (
                        (float) $dia['total']
                        / $maximoSemana
                    ) * 100;
                @endphp

                <div class="dashboard-chart__column">

                    <div class="dashboard-chart__value">
                        @if ((float) $dia['total'] > 0)
                            ${{ number_format(
                                (float) $dia['total'],
                                0
                            ) }}
                        @endif
                    </div>

                    <div class="dashboard-chart__track">
                        <div
                            class="dashboard-chart__bar"
                            style="height: {{
                                max(4, $porcentaje)
                            }}%;"
                            title="${{
                                number_format(
                                    (float) $dia['total'],
                                    2
                                )
                            }}"
                        ></div>
                    </div>

                    <div class="dashboard-chart__label">
                        {{ $dia['dia'] }}
                    </div>

                    <small class="text-muted">
                        {{ $dia['fecha'] }}
                    </small>

                </div>

            @endforeach

        </div>

    </section>
</div>

    </div>
@endsection