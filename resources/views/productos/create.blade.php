@extends('layouts.app')

@section('title', 'Nuevo producto')
@section('page-title', 'Nuevo producto')
@section('page-subtitle', 'Registra un producto y su inventario inicial')

@section('content')
    <div class="module-container">
        <div class="mb-4">
            <a
                href="{{ route('productos.index') }}"
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
                        Información del producto
                    </h2>

                    <p class="module-card__subtitle">
                        Al guardar se creará automáticamente su inventario inicial.
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('productos.store') }}"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="module-card__body">
                    @include(
                        'productos.form',
                        ['producto' => null]
                    )
                </div>

                <div class="module-card__footer">
                    <a
                        href="{{ route('productos.index') }}"
                        class="btn btn-light border"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-2"></i>
                        Guardar producto
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection