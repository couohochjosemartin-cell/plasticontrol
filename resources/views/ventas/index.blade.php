@extends('layouts.app')

@section('title', 'Historial de ventas')
@section('page-title', 'Historial de ventas')
@section('page-subtitle', 'Consulta las operaciones registradas en PlastiControl')

@section('content')
    <div class="module-container">

        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-receipt"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Ventas de hoy
                        </div>

                        <div class="stat-card__value">
                            {{ $resumen['ventas_hoy'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-cash-coin"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Ingresos de hoy
                        </div>

                        <div class="stat-card__value fs-5">
                            ${{ number_format(
                                (float) $resumen['ingresos_hoy'],
                                2
                            ) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-cart-check"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Ventas totales
                        </div>

                        <div class="stat-card__value">
                            {{ $resumen['ventas_totales'] }}
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
                            Ingresos acumulados
                        </div>

                        <div class="stat-card__value fs-5">
                            ${{ number_format(
                                (float) $resumen['ingresos_totales'],
                                2
                            ) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="module-card">
            <div class="module-card__header flex-wrap gap-3">
                <div>
                    <h2 class="module-card__title">
                        Ventas registradas
                    </h2>

                    <p class="module-card__subtitle mb-0">
                        Consulta folios, importes y operaciones anteriores.
                    </p>
                </div>

                <a
                    href="{{ route('ventas.create') }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-cart-plus me-2"></i>
                    Nueva venta
                </a>
            </div>

            <div class="module-card__body border-bottom">
                <form
                    method="GET"
                    action="{{ route('ventas.index') }}"
                    class="row g-3"
                >
                    <div class="col-12 col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                name="buscar"
                                type="search"
                                class="form-control"
                                value="{{ $buscar }}"
                                placeholder="Buscar por folio..."
                            >
                        </div>
                    </div>

                    <div class="col-6 col-lg-3">
                        <input
                            name="fecha_desde"
                            type="date"
                            class="form-control"
                            value="{{ $fechaDesde }}"
                            title="Fecha inicial"
                        >
                    </div>

                    <div class="col-6 col-lg-3">
                        <input
                            name="fecha_hasta"
                            type="date"
                            class="form-control"
                            value="{{ $fechaHasta }}"
                            title="Fecha final"
                        >
                    </div>

                    <div class="col-12 col-lg-2 d-grid">
                        <button
                            type="submit"
                            class="btn btn-outline-primary"
                        >
                            Filtrar
                        </button>
                    </div>

                    @if (
                        $buscar !== ''
                        || $fechaDesde
                        || $fechaHasta
                    )
                        <div class="col-12">
                            <a
                                href="{{ route('ventas.index') }}"
                                class="small text-decoration-none"
                            >
                                <i class="bi bi-x-circle me-1"></i>
                                Limpiar filtros
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table module-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th class="text-center">Productos</th>
                            <th>Método</th>
                            <th class="text-end">Total</th>
                            <th>Estado</th>
                            <th class="text-end">Detalle</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ventas as $venta)
                            <tr>
                                <td>
                                    <strong>
                                        {{ $venta->folio }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $venta->fecha_venta->format(
                                        'd/m/Y H:i'
                                    ) }}
                                </td>

                                <td>
                                    {{ $venta->usuario->nombre_completo }}
                                </td>

                                <td class="text-center">
                                    <span class="badge text-bg-light border">
                                        {{ $venta->detalles_count }}
                                    </span>
                                </td>

                                <td>
                                    <i class="bi bi-cash me-1"></i>
                                    {{ $venta->metodo_pago }}
                                </td>

                                <td class="text-end fw-semibold">
                                    ${{ number_format(
                                        (float) $venta->total,
                                        2
                                    ) }}
                                </td>

                                <td>
                                    @if ($venta->estado === 'Completada')
                                        <span class="badge text-bg-success">
                                            Completada
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary">
                                            {{ $venta->estado }}
                                        </span>
                                    @endif
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
                                <td colspan="8" class="py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-receipt"></i>

                                        <strong class="d-block mb-1">
                                            No hay ventas registradas
                                        </strong>

                                        <span>
                                            Las ventas aparecerán aquí después de cobrarlas.
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