@extends('layouts.app')

@section('title', 'Editar categoría')
@section('page-title', 'Editar categoría')
@section('page-subtitle', 'Actualiza la información de la categoría')

@section('content')
    <div class="module-container">
        <div class="mb-4">
            <a
                href="{{ route('categorias.index') }}"
                class="btn btn-light border"
            >
                <i class="bi bi-arrow-left me-2"></i>
                Volver
            </a>
        </div>

        <section class="module-card">
            <div class="module-card__header">
                <div>
                    <h2 class="module-card__title">
                        {{ $categoria->nombre }}
                    </h2>

                    <p class="module-card__subtitle">
                        Modifica únicamente los datos que necesites actualizar.
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('categorias.update', $categoria) }}"
            >
                @csrf
                @method('PUT')

                <div class="module-card__body">
                    @include(
                        'categorias.form',
                        ['categoria' => $categoria]
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
                        Guardar cambios
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection