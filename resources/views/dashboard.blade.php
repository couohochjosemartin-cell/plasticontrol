<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | {{ config('app.name') }}</title>
</head>

<body>
    <h1>Bienvenido a PlastiControl</h1>

    <p>
        Usuario: {{ auth()->user()->nombre_completo }}
    </p>

    <p>
        Rol: {{ auth()->user()->rol->nombre }}
    </p>

    <p>
        Último acceso:
        {{ auth()->user()->ultimo_acceso_at?->format('d/m/Y H:i:s') }}
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">
            Cerrar sesión
        </button>
    </form>
</body>
</html>