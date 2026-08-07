@extends('layouts.app')

@section('title', 'Detalle de inventario')
@section('page-title', 'Detalle de inventario')
@section('page-subtitle', 'Existencias e historial de movimientos')

@section('content')
    <div class="module-container">
        <div class="mb-4">
            <a
                href="{{ route('inventario.index') }}"
                class="btn btn-light border"
            >
                <i class="bi bi-arrow-left me-2"></i>
                Volver al inventario
            </a>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-xl-4">
                <section class="module-card h-100">
                    <div class="module-card__body">
                        <div class="module-card__icon mb-3">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <h2 class="h4 mb-1">
                            {{ $inventario->producto->nombre }}
                        </h2>

                        <div class="text-muted mb-3">
                            {{ $inventario->producto->codigo }}
                        </div>

                        <div class="mb-2">
                            Categoría:
                            <strong>
                                {{ $inventario->producto->categoria->nombre }}
                            </strong>
                        </div>

                        <div>
                            @if ($inventario->estado === 'Disponible')
                                <span class="badge text-bg-success">
                                    Disponible
                                </span>
                            @elseif ($inventario->estado === 'Stock bajo')
                                <span class="badge text-bg-warning">
                                    Stock bajo
                                </span>
                            @else
                                <span class="badge text-bg-danger">
                                    Agotado
                                </span>
                            @endif
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-8">
                <div class="row g-3 h-100">
                    <div class="col-6 col-md-4">
                        <div class="stat-card">
                            <div class="stat-card__icon">
                                <i class="bi bi-boxes"></i>
                            </div>

                            <div>
                                <div class="stat-card__label">
                                    Stock actual
                                </div>

                                <div class="stat-card__value">
                                    {{ $inventario->stock_actual }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4">
                        <div class="stat-card">
                            <div class="stat-card__icon">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>

                            <div>
                                <div class="stat-card__label">
                                    Stock mínimo
                                </div>

                                <div class="stat-card__value">
                                    {{ $inventario->stock_minimo }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <div class="stat-card__icon">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>

                            <div>
                                <div class="stat-card__label">
                                    Movimientos
                                </div>

                                <div class="stat-card__value">
                                    {{ $movimientos->total() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="module-card">
            <div class="module-card__header">
                <div>
                    <h2 class="module-card__title">
                        Historial de movimientos
                    </h2>

                    <p class="module-card__subtitle mb-0">
                        Registro cronológico de las entradas y salidas.
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table module-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Anterior</th>
                            <th>Resultante</th>
                            <th>Motivo</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($movimientos as $movimiento)
                            <tr>
                                <td>
                                    {{ $movimiento->fecha_movimiento->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    @if ($movimiento->tipo_movimiento === 'Entrada')
                                        <span class="badge text-bg-success">
                                            <i class="bi bi-arrow-down-left me-1"></i>
                                            Entrada
                                        </span>
                                    @else
                                        <span class="badge text-bg-danger">
                                            <i class="bi bi-arrow-up-right me-1"></i>
                                            Salida
                                        </span>
                                    @endif
                                </td>

                                <td class="fw-semibold">
                                    {{ $movimiento->cantidad }}
                                </td>

                                <td>
                                    {{ $movimiento->stock_anterior }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $movimiento->stock_resultante }}
                                </td>

                                <td>
                                    {{ $movimiento->motivo }}
                                </td>

                                <td>
                                    {{ $movimiento->usuario->nombre_completo }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-clock-history"></i>

                                        <strong class="d-block">
                                            Sin movimientos
                                        </strong>

                                        <span>
                                            Este producto todavía no tiene historial.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($movimientos->hasPages())
                <div class="module-card__footer">
                    <div class="w-100">
                        {{ $movimientos->links() }}
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection