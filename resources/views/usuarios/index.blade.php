@extends('layouts.app')

@section('title', 'Usuarios')
@section('page-title', 'Usuarios')
@section('page-subtitle', 'Administra las cuentas y permisos de acceso')

@section('content')
    <div class="module-container">
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-card__icon">
                        <i class="bi bi-people"></i>
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
                        <i class="bi bi-person-check"></i>
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
                        <i class="bi bi-person-dash"></i>
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
                        <i class="bi bi-trash"></i>
                    </div>

                    <div>
                        <div class="stat-card__label">Eliminados</div>
                        <div class="stat-card__value">
                            {{ $resumen['eliminados'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="module-card">
            <div class="module-card__header flex-wrap gap-3">
                <div>
                    <h2 class="module-card__title">
                        Usuarios registrados
                    </h2>

                    <p class="module-card__subtitle mb-0">
                        Consulta y administra las cuentas del sistema.
                    </p>
                </div>

                <a
                    href="{{ route('usuarios.create') }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-person-plus me-2"></i>
                    Nuevo usuario
                </a>
            </div>

            <div class="module-card__body border-bottom">
                <form
                    method="GET"
                    action="{{ route('usuarios.index') }}"
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
                                placeholder="Buscar por nombre o usuario..."
                            >
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <select
                            name="rol"
                            class="form-select"
                        >
                            <option value="">
                                Todos los roles
                            </option>

                            @foreach ($roles as $rol)
                                <option
                                    value="{{ $rol->id }}"
                                    @selected($rolId == $rol->id)
                                >
                                    {{ $rol->nombre }}
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

                            <option
                                value="eliminados"
                                @selected($estado === 'eliminados')
                            >
                                Eliminados
                            </option>
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
                            <th>Usuario</th>
                            <th>Acceso</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Último acceso</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $usuario->nombre_completo }}
                                    </div>

                                    @if ($usuario->trashed())
                                        <small class="text-danger">
                                            Eliminado
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ $usuario->usuario }}
                                </td>

                                <td>
                                    <span class="badge text-bg-light border">
                                        {{ $usuario->rol->nombre }}
                                    </span>
                                </td>

                                <td>
                                    @if ($usuario->trashed())
                                        <span class="badge text-bg-danger">
                                            Eliminado
                                        </span>
                                    @elseif ($usuario->estado === 'Activo')
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
                                    {{ $usuario->ultimo_acceso_at
                                        ? $usuario->ultimo_acceso_at->format('d/m/Y H:i')
                                        : 'Sin acceso'
                                    }}
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        @if (! $usuario->trashed())
                                            <a
                                                href="{{ route('usuarios.show', $usuario) }}"
                                                class="btn btn-sm btn-light border"
                                                title="Ver"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a
                                                href="{{ route('usuarios.edit', $usuario) }}"
                                                class="btn btn-sm btn-light border"
                                                title="Editar"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            @can('delete', $usuario)
                                                <form
                                                    method="POST"
                                                    action="{{ route('usuarios.destroy', $usuario) }}"
                                                    onsubmit="return confirm('¿Deseas eliminar este usuario?');"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                    >
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @else
                                            @can('restore', $usuario)
                                                <form
                                                    method="POST"
                                                    action="{{ route('usuarios.restore', $usuario->id) }}"
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
                                <td colspan="6" class="py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-people"></i>

                                        <strong class="d-block mb-1">
                                            No encontramos usuarios
                                        </strong>

                                        <span>
                                            Registra una nueva cuenta o modifica los filtros.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($usuarios->hasPages())
                <div class="module-card__footer">
                    <div class="w-100">
                        {{ $usuarios->links() }}
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection