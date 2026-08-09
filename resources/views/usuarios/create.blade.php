@extends('layouts.app')

@section('title', 'Nuevo usuario')
@section('page-title', 'Nuevo usuario')
@section('page-subtitle', 'Registra una cuenta de acceso a PlastiControl')

@section('content')
    <div class="module-container">
        <div class="mb-4">
            <a
                href="{{ route('usuarios.index') }}"
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
                        Información del usuario
                    </h2>

                    <p class="module-card__subtitle">
                        Define sus datos de acceso y permisos.
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-person-plus"></i>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('usuarios.store') }}"
            >
                @csrf

                <div class="module-card__body">
                    @include(
                        'usuarios.form',
                        ['usuario' => null]
                    )
                </div>

                <div class="module-card__footer">
                    <a
                        href="{{ route('usuarios.index') }}"
                        class="btn btn-light border"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-2"></i>
                        Guardar usuario
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection