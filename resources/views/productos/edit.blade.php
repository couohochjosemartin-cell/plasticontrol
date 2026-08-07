@extends('layouts.app')

@section('title', 'Editar producto')
@section('page-title', 'Editar producto')
@section('page-subtitle', 'Actualiza la información comercial del producto')

@section('content')
    <div class="module-container">
        <div class="mb-4">
            <a
                href="{{ route('productos.show', $producto) }}"
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
                        {{ $producto->nombre }}
                    </h2>

                    <p class="module-card__subtitle">
                        Código {{ $producto->codigo }}
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('productos.update', $producto) }}"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                <div class="module-card__body">
                    @include(
                        'productos.form',
                        ['producto' => $producto]
                    )
                </div>

                <div class="module-card__footer">
                    <a
                        href="{{ route('productos.show', $producto) }}"
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