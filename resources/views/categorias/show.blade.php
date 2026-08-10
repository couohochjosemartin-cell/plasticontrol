@extends('layouts.app')

@section('title', 'Detalle de categoría')
@section('page-title', 'Detalle de categoría')
@section('page-subtitle', 'Información general de la categoría seleccionada')

@section('content')
    <div class="module-container">
        <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
            <a
                href="{{ route('categorias.index') }}"
                class="btn btn-light border"
            >
                <i class="bi bi-arrow-left me-2"></i>
                Volver
            </a>

            @can('update', $categoria)
                <a
                    href="{{ route('categorias.edit', $categoria) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-pencil me-2"></i>
                    Editar categoría
                </a>
            @endcan
        </div>

        <section class="module-card">
            <div class="module-card__header">
                <div>
                    <span
                        class="badge {{ $categoria->estado === \App\Enums\EstadoCategoria::ACTIVA ? 'text-bg-success': 'text-bg-secondary' }}" mb-2"
                    >
                        {{ $categoria->estado }}
                    </span>

                    <h2 class="module-card__title">
                        {{ $categoria->nombre }}
                    </h2>

                    <p class="module-card__subtitle mb-0">
                        ID #{{ $categoria->id }}
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-tag"></i>
                </div>
            </div>

            <div class="module-card__body">
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="detail-item">
                            <span class="detail-item__label">
                                Nombre
                            </span>

                            <strong class="detail-item__value">
                                {{ $categoria->nombre }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="detail-item">
                            <span class="detail-item__label">
                                Productos asociados
                            </span>

                            <strong class="detail-item__value">
                                {{ $categoria->productos_count }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="detail-item">
                            <span class="detail-item__label">
                                Descripción
                            </span>

                            <div class="detail-item__value">
                                {{ $categoria->descripcion ?: 'Sin descripción registrada.' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="detail-item">
                            <span class="detail-item__label">
                                Registrada
                            </span>

                            <div class="detail-item__value">
                                {{ $categoria->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="detail-item">
                            <span class="detail-item__label">
                                Última actualización
                            </span>

                            <div class="detail-item__value">
                                {{ $categoria->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection