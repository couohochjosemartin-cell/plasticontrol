@extends('layouts.app')

@section('title', 'Reportes')
@section('page-title', 'Reportes')
@section('page-subtitle', 'Analiza ventas, ganancias e indicadores del negocio')

@section('content')
    <div class="module-container">

        {{-- =========================
             FILTRO POR PERIODO
        ========================== --}}
        <section class="module-card mb-4">
            <div class="module-card__header">
                <div>
                    <h2 class="module-card__title">
                        Periodo del reporte
                    </h2>

                    <p class="module-card__subtitle mb-0">
                        Selecciona el rango de fechas que deseas analizar.
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-calendar-range"></i>
                </div>
            </div>

            <div class="module-card__body">
                <form
                    method="GET"
                    action="{{ route('reportes.index') }}"
                    class="row g-3 align-items-end"
                >
                    <div class="col-12 col-md-4">
                        <label
                            for="fecha_desde"
                            class="form-label"
                        >
                            Desde
                        </label>

                        <input
                            id="fecha_desde"
                            name="fecha_desde"
                            type="date"
                            class="form-control"
                            value="{{ $fechaDesde }}"
                            required
                        >
                    </div>

                    <div class="col-12 col-md-4">
                        <label
                            for="fecha_hasta"
                            class="form-label"
                        >
                            Hasta
                        </label>

                        <input
                            id="fecha_hasta"
                            name="fecha_hasta"
                            type="date"
                            class="form-control"
                            value="{{ $fechaHasta }}"
                            required
                        >
                    </div>

                    <div class="col-12 col-md-4 d-grid">
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-funnel me-2"></i>
                            Generar reporte
                        </button>
                    </div>
                </form>
            </div>
        </section>

        {{-- =========================
             INDICADORES PRINCIPALES
        ========================== --}}
        <div class="row g-3 mb-4">

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Ingresos
                        </div>

                        <div class="stat-card__value fs-5">
                            ${{ number_format((float) $ingresos, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Ganancia estimada
                        </div>

                        <div class="stat-card__value fs-5">
                            ${{ number_format((float) $ganancia, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-receipt"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Ventas
                        </div>

                        <div class="stat-card__value">
                            {{ $numeroVentas }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-calculator"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Ticket promedio
                        </div>

                        <div class="stat-card__value fs-5">
                            ${{ number_format(
                                (float) $ticketPromedio,
                                2
                            ) }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- SEGUNDA FILA --}}
        <div class="row g-3 mb-4">

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Unidades vendidas
                        </div>

                        <div class="stat-card__value">
                            {{ $unidadesVendidas }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-tag"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Descuentos
                        </div>

                        <div class="stat-card__value fs-5">
                            ${{ number_format(
                                (float) $descuentos,
                                2
                            ) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-2">
                <div class="stat-card">
                    <div>
                        <div class="stat-card__label">
                            Disponibles
                        </div>

                        <div class="stat-card__value text-success">
                            {{ $inventario['disponibles'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-2">
                <div class="stat-card">
                    <div>
                        <div class="stat-card__label">
                            Stock bajo
                        </div>

                        <div class="stat-card__value text-warning">
                            {{ $inventario['stock_bajo'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-2">
                <div class="stat-card">
                    <div>
                        <div class="stat-card__label">
                            Agotados
                        </div>

                        <div class="stat-card__value text-danger">
                            {{ $inventario['agotados'] }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- =========================
             GRÁFICA + TOP PRODUCTOS
        ========================== --}}
        <div class="row g-4 mb-4">

            <div class="col-12 col-xl-8">
                <section class="module-card h-100">
                    <div class="module-card__header">
                        <div>
                            <h2 class="module-card__title">
                                Ventas por día
                            </h2>

                            <p class="module-card__subtitle mb-0">
                                Comportamiento de ingresos dentro del periodo.
                            </p>
                        </div>

                        <div class="module-card__icon">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                    </div>

                    <div class="module-card__body">

                        @php
                            $maxVentaDia = max(
                                1,
                                (float) $ventasPorDia->max('total')
                            );
                        @endphp

                        @if ($ventasPorDia->isNotEmpty())
                            <div class="report-chart">
                                @foreach ($ventasPorDia as $dia)
                                    @php
                                        $altura = (
                                            (float) $dia->total
                                            / $maxVentaDia
                                        ) * 100;
                                    @endphp

                                    <div class="report-chart__column">
                                        <div class="report-chart__amount">
                                            ${{ number_format(
                                                (float) $dia->total,
                                                0
                                            ) }}
                                        </div>

                                        <div class="report-chart__track">
                                            <div
                                                class="report-chart__bar"
                                                style="height: {{ max(4, $altura) }}%;"
                                            ></div>
                                        </div>

                                        <div class="report-chart__date">
                                            {{ \Carbon\Carbon::parse(
                                                $dia->fecha
                                            )->format('d/m') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state py-5">
                                <i class="bi bi-bar-chart"></i>

                                <strong class="d-block mb-1">
                                    Sin ventas en este periodo
                                </strong>

                                <span>
                                    Cambia las fechas o registra nuevas ventas.
                                </span>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-4">
                <section class="module-card h-100">
                    <div class="module-card__header">
                        <div>
                            <h2 class="module-card__title">
                                Productos más vendidos
                            </h2>

                            <p class="module-card__subtitle mb-0">
                                Top 10 por unidades.
                            </p>
                        </div>

                        <div class="module-card__icon">
                            <i class="bi bi-trophy"></i>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table module-table mb-0">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">
                                        Uds.
                                    </th>
                                    <th class="text-end">
                                        Importe
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse (
                                    $productosMasVendidos as $producto
                                )
                                    <tr>
                                        <td>
                                            <strong>
                                                {{ $producto->producto?->nombre
                                                    ?? 'Producto eliminado'
                                                }}
                                            </strong>
                                        </td>

                                        <td class="text-center">
                                            {{ $producto->unidades }}
                                        </td>

                                        <td class="text-end">
                                            ${{ number_format(
                                                (float) $producto->importe,
                                                2
                                            ) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="3"
                                            class="text-center py-4 text-muted"
                                        >
                                            Sin información
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

        </div>

        {{-- =========================
             VENTAS POR USUARIO
        ========================== --}}
        <section class="module-card mb-4">
            <div class="module-card__header">
                <div>
                    <h2 class="module-card__title">
                        Ventas por usuario
                    </h2>

                    <p class="module-card__subtitle mb-0">
                        Rendimiento de los usuarios que registraron ventas.
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table module-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th class="text-center">
                                Ventas
                            </th>
                            <th class="text-end">
                                Total vendido
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ventasPorUsuario as $registro)
                            <tr>
                                <td>
                                    <strong>
                                        {{ $registro->usuario?->nombre_completo
                                            ?? 'Usuario eliminado'
                                        }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $registro->usuario?->rol?->nombre
                                        ?? 'Sin rol'
                                    }}
                                </td>

                                <td class="text-center">
                                    {{ $registro->ventas }}
                                </td>

                                <td class="text-end fw-semibold">
                                    ${{ number_format(
                                        (float) $registro->total,
                                        2
                                    ) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="4"
                                    class="text-center py-4 text-muted"
                                >
                                    No existen ventas para este periodo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- =========================
             OPERACIONES
        ========================== --}}
        <section class="module-card">
            <div class="module-card__header">
                <div>
                    <h2 class="module-card__title">
                        Operaciones del periodo
                    </h2>

                    <p class="module-card__subtitle mb-0">
                        Ventas incluidas en el reporte seleccionado.
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table module-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Método</th>
                            <th>Descuento</th>
                            <th class="text-end">
                                Total
                            </th>
                            <th class="text-end">
                                Detalle
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ventas as $venta)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $venta->folio }}
                                </td>

                                <td>
                                    {{ $venta->fecha_venta->format(
                                        'd/m/Y H:i'
                                    ) }}
                                </td>

                                <td>
                                    {{ $venta->usuario?->nombre_completo
                                        ?? 'Usuario eliminado'
                                    }}
                                </td>

                                <td>
                                    {{ $venta->metodo_pago }}
                                </td>

                                <td>
                                    ${{ number_format(
                                        (float) $venta->descuento,
                                        2
                                    ) }}
                                </td>

                                <td class="text-end fw-semibold">
                                    ${{ number_format(
                                        (float) $venta->total,
                                        2
                                    ) }}
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a
                                            href="{{ route(
                                                'ventas.show',
                                                $venta
                                            ) }}"
                                            class="btn btn-sm btn-light border"
                                            title="Ver venta"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-receipt"></i>

                                        <strong class="d-block mb-1">
                                            Sin operaciones
                                        </strong>

                                        <span>
                                            No existen ventas dentro del periodo seleccionado.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($ventas->hasPages())
                <div class="module-card__footer">
                    <div class="w-100">
                        {{ $ventas->links() }}
                    </div>
                </div>
            @endif
        </section>

    </div>
@endsection