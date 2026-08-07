@extends('layouts.app')

@section('title', 'Categorías')
@section('page-title', 'Categorías')
@section('page-subtitle', 'Administra la clasificación de los productos')

@section('content')
    <div class="module-container">
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-tags"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">
                            Total
                        </div>

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
                        <div class="stat-card__label">
                            Activas
                        </div>

                        <div class="stat-card__value">
                            {{ $resumen['activas'] }}
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
                        <div class="stat-card__label">
                            Inactivas
                        </div>

                        <div class="stat-card__value">
                            {{ $resumen['inactivas'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-box-seam"></i>
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
        </div>

        <section class="module-card">
            <div class="module-card__header flex-wrap gap-3">
                <div>
                    <h2 class="module-card__title">
                        Categorías registradas
                    </h2>

                    <p class="module-card__subtitle mb-0">
                        Busca, consulta y administra las categorías del sistema.
                    </p>
                </div>

                @can('create', App\Models\Categoria::class)
                    <a
                        href="{{ route('categorias.create') }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-2"></i>
                        Nueva categoría
                    </a>
                @endcan
            </div>

            <div class="module-card__body border-bottom">
                <form
                    method="GET"
                    action="{{ route('categorias.index') }}"
                    class="row g-3"
                >
                    <div class="col-12 col-lg-7">
                        <label
                            for="buscar"
                            class="visually-hidden"
                        >
                            Buscar categoría
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                id="buscar"
                                name="buscar"
                                type="search"
                                class="form-control"
                                value="{{ $buscar }}"
                                placeholder="Buscar por nombre o descripción..."
                            >
                        </div>
                    </div>

                    <div class="col-8 col-lg-3">
                        <select
                            name="estado"
                            class="form-select"
                        >
                            <option
                                value="todas"
                                @selected($estado === 'todas')
                            >
                                Todas
                            </option>

                            <option
                                value="Activa"
                                @selected($estado === 'Activa')
                            >
                                Activas
                            </option>

                            <option
                                value="Inactiva"
                                @selected($estado === 'Inactiva')
                            >
                                Inactivas
                            </option>

                            @if (auth()->user()->esAdministrador())
                                <option
                                    value="eliminadas"
                                    @selected($estado === 'eliminadas')
                                >
                                    Eliminadas
                                </option>
                            @endif
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

                    @if ($buscar !== '' || $estado !== 'todas')
                        <div class="col-12">
                            <a
                                href="{{ route('categorias.index') }}"
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
                            <th>ID</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th class="text-center">Productos</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($categorias as $categoria)
                            <tr>
                                <td class="text-muted">
                                    #{{ $categoria->id }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $categoria->nombre }}
                                    </div>

                                    @if ($categoria->trashed())
                                        <small class="text-danger">
                                            Eliminada
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    <span class="text-muted">
                                        {{ Str::limit(
                                            $categoria->descripcion ?: 'Sin descripción',
                                            55
                                        ) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge text-bg-light border">
                                        {{ $categoria->productos_count }}
                                    </span>
                                </td>

                                <td>
                                    @if ($categoria->trashed())
                                        <span class="badge text-bg-danger">
                                            Eliminada
                                        </span>
                                    @elseif ($categoria->estado === 'Activa')
                                        <span class="badge text-bg-success">
                                            Activa
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary">
                                            Inactiva
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        @if (! $categoria->trashed())
                                            <a
                                                href="{{ route('categorias.show', $categoria) }}"
                                                class="btn btn-sm btn-light border"
                                                title="Ver detalles"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @can('update', $categoria)
                                                <a
                                                    href="{{ route('categorias.edit', $categoria) }}"
                                                    class="btn btn-sm btn-light border"
                                                    title="Editar"
                                                >
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $categoria)
                                                <form
                                                    method="POST"
                                                    action="{{ route('categorias.destroy', $categoria) }}"
                                                    onsubmit="return confirm('¿Deseas eliminar esta categoría? Podrás restaurarla posteriormente.');"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Eliminar"
                                                    >
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @else
                                            @can('restore', $categoria)
                                                <form
                                                    method="POST"
                                                    action="{{ route('categorias.restore', $categoria->id) }}"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-success"
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
                                <td
                                    colspan="6"
                                    class="py-5"
                                >
                                    <div class="empty-state">
                                        <i class="bi bi-tags"></i>

                                        <strong class="d-block mb-1">
                                            No encontramos categorías
                                        </strong>

                                        <span>
                                            Cambia los filtros o registra una nueva categoría.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categorias->hasPages())
                <div class="module-card__footer">
                    <div class="w-100">
                        {{ $categorias->links() }}
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection