@extends('layouts.app')

@section('title', 'Inventario')
@section('page-title', 'Inventario')
@section('page-subtitle', 'Controla existencias y movimientos de mercancía')

@section('content')

@if ($errors->any())
    <div
        class="alert alert-danger alert-dismissible fade show"
        role="alert"
    >
        <i class="bi bi-exclamation-triangle me-2"></i>

        <strong>No se pudo realizar el movimiento.</strong>

        <div class="mt-1">
            {{ $errors->first() }}
        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Cerrar"
        ></button>
    </div>
@endif

    <div class="module-container">
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-boxes"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Productos
                        </div>

                        <div class="stat-card__value">
                            {{ $resumen['productos'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-check-circle"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Disponibles
                        </div>

                        <div class="stat-card__value">
                            {{ $resumen['disponibles'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Stock bajo
                        </div>

                        <div class="stat-card__value">
                            {{ $resumen['stock_bajo'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-x-circle"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Agotados
                        </div>

                        <div class="stat-card__value">
                            {{ $resumen['agotados'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="module-card">
            <div class="module-card__header">
                <div>
                    <h2 class="module-card__title">
                        Existencias actuales
                    </h2>

                    <p class="module-card__subtitle mb-0">
                        Consulta y ajusta el inventario de los productos.
                    </p>
                </div>
            </div>

            <div class="module-card__body border-bottom">
                <form
                    method="GET"
                    action="{{ route('inventario.index') }}"
                    class="row g-3"
                >
                    <div class="col-12 col-lg-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                name="buscar"
                                type="search"
                                class="form-control"
                                value="{{ $buscar }}"
                                placeholder="Buscar por producto o código..."
                            >
                        </div>
                    </div>

                    <div class="col-8 col-lg-3">
                        <select
                            name="estado"
                            class="form-select"
                        >
                            <option
                                value="todos"
                                @selected($estado === 'todos')
                            >
                                Todos
                            </option>

                            <option
                                value="Disponible"
                                @selected($estado === 'Disponible')
                            >
                                Disponible
                            </option>

                            <option
                                value="Stock bajo"
                                @selected($estado === 'Stock bajo')
                            >
                                Stock bajo
                            </option>

                            <option
                                value="Agotado"
                                @selected($estado === 'Agotado')
                            >
                                Agotado
                            </option>
                        </select>
                    </div>

                    <div class="col-4 col-lg-2 d-grid">
                        <button
                            type="submit"
                            class="btn btn-outline-primary"
                        >
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table module-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Categoría</th>
                            <th class="text-center">Stock actual</th>
                            <th class="text-center">Stock mínimo</th>
                            <th>Estado</th>
                            <th class="text-end">Movimiento</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($inventarios as $inventario)
                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $inventario->producto->nombre }}
                                    </div>
                                </td>

                                <td>
                                    {{ $inventario->producto->codigo }}
                                </td>

                                <td>
                                    {{ $inventario->producto->categoria->nombre }}
                                </td>

                                <td class="text-center">
                                    <span class="fw-bold">
                                        {{ $inventario->stock_actual }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    {{ $inventario->stock_minimo }}
                                </td>

                                <td>
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
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end gap-2">
    <a
        href="{{ route('inventario.show', $inventario) }}"
        class="btn btn-sm btn-light border"
        title="Ver historial"
    >
        <i class="bi bi-eye"></i>
    </a>

    <button
                                            type="button"
                                            class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#movimientoModal{{ $inventario->id }}"
                                        >
                                            <i class="bi bi-arrow-left-right me-1"></i>
                                            Ajustar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div
                                class="modal fade"
                                id="movimientoModal{{ $inventario->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <form
                                            method="POST"
                                            action="{{ route('inventario.movimiento', $inventario) }}"
                                        >
                                            @csrf

                                            <div class="modal-header">
                                                <div>
                                                    <h2 class="modal-title fs-5">
                                                        Movimiento de inventario
                                                    </h2>

                                                    <div class="text-muted small">
                                                        {{ $inventario->producto->nombre }}
                                                        ·
                                                        {{ $inventario->producto->codigo }}
                                                    </div>
                                                </div>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Cerrar"
                                                ></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="alert alert-light border">
                                                    Stock actual:
                                                    <strong>
                                                        {{ $inventario->stock_actual }}
                                                    </strong>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Tipo de movimiento
                                                    </label>

                                                    <select
                                                        name="tipo_movimiento"
                                                        class="form-select"
                                                        required
                                                    >
                                                        <option value="Entrada">
                                                            Entrada
                                                        </option>

                                                        <option value="Salida">
                                                            Salida
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        Cantidad
                                                    </label>

                                                    <input
                                                        name="cantidad"
                                                        type="number"
                                                        min="1"
                                                        class="form-control"
                                                        required
                                                    >
                                                </div>

                                                <div>
                                                    <label class="form-label">
                                                        Motivo
                                                    </label>

                                                    <textarea
                                                        name="motivo"
                                                        rows="3"
                                                        maxlength="255"
                                                        class="form-control"
                                                        placeholder="Ej. Entrada de nueva mercancía"
                                                        required
                                                    ></textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button
                                                    type="button"
                                                    class="btn btn-light border"
                                                    data-bs-dismiss="modal"
                                                >
                                                    Cancelar
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="btn btn-primary"
                                                >
                                                    Registrar movimiento
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-boxes"></i>

                                        <strong class="d-block mb-1">
                                            No hay inventarios
                                        </strong>

                                        <span>
                                            Registra productos para comenzar a controlar existencias.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($inventarios->hasPages())
                <div class="module-card__footer">
                    <div class="w-100">
                        {{ $inventarios->links() }}
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection