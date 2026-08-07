@extends('layouts.app')

@section('title', 'Productos')
@section('page-title', 'Productos')
@section('page-subtitle', 'Administra el catálogo comercial y sus existencias')

@section('content')
    <div class="module-container">
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">Total</div>
                        <div class="stat-card__value">
                            {{ $resumen['total'] }}
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
                        <div class="stat-card__label">Activos</div>
                        <div class="stat-card__value">
                            {{ $resumen['activos'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-pause-circle"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">Inactivos</div>
                        <div class="stat-card__value">
                            {{ $resumen['inactivos'] }}
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
                        <div class="stat-card__label">Stock bajo</div>
                        <div class="stat-card__value">
                            {{ $resumen['stock_bajo'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="module-card">
            <div class="module-card__header flex-wrap gap-3">
                <div>
                    <h2 class="module-card__title">
                        Productos registrados
                    </h2>

                    <p class="module-card__subtitle mb-0">
                        Consulta precios, categorías y existencias.
                    </p>
                </div>

                @can('create', App\Models\Producto::class)
                    <a
                        href="{{ route('productos.create') }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-2"></i>
                        Nuevo producto
                    </a>
                @endcan
            </div>

            <div class="module-card__body border-bottom">
                <form
                    method="GET"
                    action="{{ route('productos.index') }}"
                    class="row g-3"
                >
                    <div class="col-12 col-xl-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                name="buscar"
                                type="search"
                                class="form-control"
                                value="{{ $buscar }}"
                                placeholder="Buscar por código, nombre o descripción..."
                            >
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <select
                            name="categoria"
                            class="form-select"
                        >
                            <option value="">
                                Todas las categorías
                            </option>

                            @foreach ($categorias as $categoria)
                                <option
                                    value="{{ $categoria->id }}"
                                    @selected($categoriaId == $categoria->id)
                                >
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-xl-2">
                        <select
                            name="estado"
                            class="form-select"
                        >
                            <option value="todos" @selected($estado === 'todos')>
                                Todos
                            </option>

                            <option value="Activo" @selected($estado === 'Activo')>
                                Activos
                            </option>

                            <option value="Inactivo" @selected($estado === 'Inactivo')>
                                Inactivos
                            </option>

                            @if (auth()->user()->esAdministrador())
                                <option
                                    value="eliminados"
                                    @selected($estado === 'eliminados')
                                >
                                    Eliminados
                                </option>
                            @endif
                        </select>
                    </div>

                    <div class="col-12 col-xl-2 d-grid">
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
                            <th>Venta</th>
                            <th class="text-center">Stock</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($productos as $producto)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($producto->imagen)
                                            <img
                                                src="{{ asset('storage/' . $producto->imagen) }}"
                                                alt="{{ $producto->nombre }}"
                                                class="product-table-image"
                                            >
                                        @else
                                            <div class="product-table-placeholder">
                                                <i class="bi bi-box"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <div class="fw-semibold">
                                                {{ $producto->nombre }}
                                            </div>

                                            @if ($producto->trashed())
                                                <small class="text-danger">
                                                    Eliminado
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $producto->codigo }}</td>

                                <td>
                                    {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                                </td>

                                <td>
                                    ${{ number_format((float) $producto->precio_venta, 2) }}
                                </td>

                                <td class="text-center">
                                    <span class="badge text-bg-light border">
                                        {{ $producto->inventario?->stock_actual ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    @if ($producto->trashed())
                                        <span class="badge text-bg-danger">
                                            Eliminado
                                        </span>
                                    @elseif ($producto->estado === 'Activo')
                                        <span class="badge text-bg-success">
                                            Activo
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        @if (! $producto->trashed())
                                            <a
                                                href="{{ route('productos.show', $producto) }}"
                                                class="btn btn-sm btn-light border"
                                                title="Ver"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @can('update', $producto)
                                                <a
                                                    href="{{ route('productos.edit', $producto) }}"
                                                    class="btn btn-sm btn-light border"
                                                    title="Editar"
                                                >
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $producto)
                                                <form
                                                    method="POST"
                                                    action="{{ route('productos.destroy', $producto) }}"
                                                    onsubmit="return confirm('¿Deseas eliminar este producto?');"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        class="btn btn-sm btn-outline-danger"
                                                        type="submit"
                                                    >
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @else
                                            @can('restore', $producto)
                                                <form
                                                    method="POST"
                                                    action="{{ route('productos.restore', $producto->id) }}"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        class="btn btn-sm btn-outline-success"
                                                        type="submit"
                                                    >
                                                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                        Restaurar
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-box-seam"></i>

                                        <strong class="d-block mb-1">
                                            No hay productos
                                        </strong>

                                        <span>
                                            Registra el primer producto del catálogo.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($productos->hasPages())
                <div class="module-card__footer">
                    <div class="w-100">
                        {{ $productos->links() }}
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection