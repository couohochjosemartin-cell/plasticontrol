@extends('layouts.app')

@section('title', 'Detalle de usuario')
@section('page-title', 'Detalle de usuario')
@section('page-subtitle', 'Información general de la cuenta')

@section('content')
    <div class="module-container">
        <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
            <a
                href="{{ route('usuarios.index') }}"
                class="btn btn-light border"
            >
                <i class="bi bi-arrow-left me-2"></i>
                Volver
            </a>

            @can('update', $usuario)
                <a
                    href="{{ route('usuarios.edit', $usuario) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-pencil me-2"></i>
                    Editar usuario
                </a>
            @endcan
        </div>

        <section class="module-card">
            <div class="module-card__header">
                <div>
                    <span
                        class="badge {{ $usuario->estado === 'Activo' ? 'text-bg-success' : 'text-bg-secondary' }} mb-2"
                    >
                        {{ $usuario->estado }}
                    </span>

                    <h2 class="module-card__title">
                        {{ $usuario->nombre_completo }}
                    </h2>

                    <p class="module-card__subtitle mb-0">
                        {{ '@' . $usuario->usuario }}
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-person"></i>
                </div>
            </div>

            <div class="module-card__body">
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="detail-item">
                            <span class="detail-item__label">
                                Rol
                            </span>

                            <strong class="detail-item__value">
                                {{ $usuario->rol->nombre }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="detail-item">
                            <span class="detail-item__label">
                                Último acceso
                            </span>

                            <div class="detail-item__value">
                                {{ $usuario->ultimo_acceso_at
                                    ? $usuario->ultimo_acceso_at->format('d/m/Y H:i')
                                    : 'Todavía no ha iniciado sesión'
                                }}
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="detail-item">
                            <span class="detail-item__label">
                                Creado por
                            </span>

                            <div class="detail-item__value">
                                {{ $usuario->creadoPor?->nombre_completo
                                    ?? 'Usuario inicial del sistema'
                                }}
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="detail-item">
                            <span class="detail-item__label">
                                Fecha de registro
                            </span>

                            <div class="detail-item__value">
                                {{ $usuario->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection