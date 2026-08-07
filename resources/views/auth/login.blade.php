<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="description"
        content="Acceso al sistema de gestión PlastiControl"
    >

    <title>Iniciar sesión | {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <main
        class="login-page d-flex align-items-center justify-content-center"
    >
        <section
            class="login-card card bg-white"
            aria-labelledby="login-title"
        >
            <header class="login-card__header">
                <div class="brand-mark" aria-hidden="true">
                    PC
                </div>

                <h1
                    id="login-title"
                    class="brand-title h2"
                >
                    PlastiControl
                </h1>

                <p class="brand-subtitle">
                    Sistema de gestión comercial
                </p>
            </header>

            <div class="login-card__body">
                @if (session('estado'))
                    <div
                        class="alert alert-success"
                        role="status"
                    >
                        {{ session('estado') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="alert alert-danger"
                        role="alert"
                    >
                        <strong>No pudimos iniciar tu sesión.</strong>

                        <div class="mt-1">
                            {{ $errors->first() }}
                        </div>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                    novalidate
                >
                    @csrf

                    <div class="mb-3">
                        <label
                            for="usuario"
                            class="form-label"
                        >
                            Usuario
                        </label>

                        <input
                            id="usuario"
                            name="usuario"
                            type="text"
                            class="form-control @error('usuario') is-invalid @enderror"
                            value="{{ old('usuario') }}"
                            maxlength="50"
                            autocomplete="username"
                            placeholder="Escribe tu usuario"
                            required
                            autofocus
                        >

                        @error('usuario')
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
                            Contraseña
                        </label>

                        <div class="input-group password-group">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                autocomplete="current-password"
                                placeholder="Escribe tu contraseña"
                                required
                            >

                            <button
                                id="toggle-password"
                                class="btn password-toggle"
                                type="button"
                                aria-label="Mostrar contraseña"
                            >
                                Mostrar
                            </button>
                        </div>

                        @error('password')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input
                            id="recordarme"
                            name="recordarme"
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            @checked(old('recordarme'))
                        >

                        <label
                            for="recordarme"
                            class="form-check-label"
                        >
                            Mantener mi sesión iniciada
                        </label>
                    </div>

                    <button
                        class="btn btn-login w-100"
                        type="submit"
                    >
                        Iniciar sesión
                    </button>
                </form>

                <footer class="login-footer mt-4">
                    <div>
                        {{ config('plasticontrol.negocio.nombre') }}
                    </div>

                    <div>
                        PlastiControl v{{ config('plasticontrol.version', '1.0') }}
                    </div>
                </footer>
            </div>
        </section>
    </main>
</body>
</html>