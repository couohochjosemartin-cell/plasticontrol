@extends('layouts.app')

@section('title', 'Editar usuario')
@section('page-title', 'Editar usuario')
@section('page-subtitle', 'Actualiza los datos y permisos de la cuenta')

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
                        {{ $usuario->nombre_completo }}
                    </h2>

                    <p class="module-card__subtitle">
                        Usuario: {{ $usuario->usuario }}
                    </p>
                </div>

                <div class="module-card__icon">
                    <i class="bi bi-person-gear"></i>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('usuarios.update', $usuario) }}"
            >
                @csrf
                @method('PUT')

                <div class="module-card__body">
                    @include(
                        'usuarios.form',
                        ['usuario' => $usuario]
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
                        Guardar cambios
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection