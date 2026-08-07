@extends('layouts.app')

@section('title', 'Nueva categoría')
@section('page-title', 'Nueva categoría')
@section('page-subtitle', 'Registra una nueva clasificación para tus productos')

@section('content')
    <div class="module-container">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <a
                    href="{{ route('categorias.index') }}"
                    class="btn btn-light border"
                >
                    <i class="bi bi-arrow-left me-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <section class="module-card">
            <div class="module-card__header">
                <div>
                    <h2 class="module-card__title">
                        Información de la categoría
                    </h2>

                    <p class="module-card__subtitle">
                        Completa los datos necesarios para registrar la categoría.
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-tags"></i>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('categorias.store') }}"
            >
                @csrf

                <div class="module-card__body">
                    @include(
                        'categorias.form',
                        ['categoria' => null]
                    )
                </div>

                <div class="module-card__footer">
                    <a
                        href="{{ route('categorias.index') }}"
                        class="btn btn-light border"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-2"></i>
                        Guardar categoría
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection