@extends('layouts.app')

@section('title', 'Cambiar contraseña')
@section('page-title', 'Cambiar contraseña')
@section('page-subtitle', 'Actualiza la contraseña de tu cuenta')

@section('content')
    <div class="module-container">

        <div class="row justify-content-center">
            <div class="col-12 col-lg-7 col-xl-6">

                <section class="module-card">

                    <div class="module-card__header">
                        <div>
                            <h2 class="module-card__title">
                                Seguridad de la cuenta
                            </h2>

                            <p class="module-card__subtitle mb-0">
                                Ingresa tu contraseña actual y establece una nueva.
                            </p>
                        </div>

                        <div class="module-card__icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('perfil.password.update') }}"
                    >
                        @csrf
                        @method('PUT')

                        <div class="module-card__body">

                            <div class="mb-3">
                                <label
                                    for="password_actual"
                                    class="form-label"
                                >
                                    Contraseña actual
                                </label>

                                <input
                                    id="password_actual"
                                    name="password_actual"
                                    type="password"
                                    class="form-control @error('password_actual') is-invalid @enderror"
                                    autocomplete="current-password"
                                    required
                                >

                                @error('password_actual')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label
                                    for="password"
                                    class="form-label"
                                >
                                    Nueva contraseña
                                </label>

                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    minlength="8"
                                    class="form-control @error('password') is-invalid @enderror"
                                    autocomplete="new-password"
                                    required
                                >

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Utiliza al menos 8 caracteres.
                                </div>
                            </div>

                            <div>
                                <label
                                    for="password_confirmation"
                                    class="form-label"
                                >
                                    Confirmar nueva contraseña
                                </label>

                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    minlength="8"
                                    class="form-control"
                                    autocomplete="new-password"
                                    required
                                >
                            </div>

                        </div>

                        <div class="module-card__footer">

                            <a
                                href="{{ route('dashboard') }}"
                                class="btn btn-light border"
                            >
                                Cancelar
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-key me-2"></i>
                                Actualizar contraseña
                            </button>

                        </div>

                    </form>

                </section>

            </div>
        </div>

    </div>
@endsection