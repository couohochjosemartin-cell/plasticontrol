<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Dashboard') | {{ config('app.name') }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="app-body">
    <div class="app-shell">
        <aside
            id="app-sidebar"
            class="app-sidebar"
        >
            <div class="sidebar-brand">
                <div class="sidebar-brand__mark">
                    PC
                </div>

                <div>
                    <div class="sidebar-brand__title">
                        PlastiControl
                    </div>

                    <div class="sidebar-brand__subtitle">
                        Gestión comercial
                    </div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a
                    href="{{ route('dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                @if (auth()->user()->esAdministrador() || auth()->user()->esCajero())
                    <a
                        href="#"
                        class="sidebar-link disabled"
                        aria-disabled="true"
                    >
                        <i class="bi bi-cart3"></i>
                        <span>Punto de Venta</span>
                    </a>
                @endif

                @if (auth()->user()->esAdministrador() || auth()->user()->esInventario())
                    <a
                        href="#"
                        class="sidebar-link disabled"
                        aria-disabled="true"
                    >
                        <i class="bi bi-box-seam"></i>
                        <span>Productos</span>
                    </a>

                    <a
                        href="#"
                        class="sidebar-link disabled"
                        aria-disabled="true"
                    >
                        <i class="bi bi-tags"></i>
                        <span>Categorías</span>
                    </a>

                    <a
                        href="#"
                        class="sidebar-link disabled"
                        aria-disabled="true"
                    >
                        <i class="bi bi-clipboard-data"></i>
                        <span>Inventario</span>
                    </a>
                @endif

                @if (auth()->user()->esAdministrador())
                    <a
                        href="#"
                        class="sidebar-link disabled"
                        aria-disabled="true"
                    >
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Reportes</span>
                    </a>

                    <a
                        href="#"
                        class="sidebar-link disabled"
                        aria-disabled="true"
                    >
                        <i class="bi bi-people"></i>
                        <span>Usuarios</span>
                    </a>

                    <a
                        href="#"
                        class="sidebar-link disabled"
                        aria-disabled="true"
                    >
                        <i class="bi bi-gear"></i>
                        <span>Configuración</span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <div>
                    {{ config('plasticontrol.negocio.nombre') }}
                </div>

                <small>
                    Versión {{ config('plasticontrol.version', '1.0') }}
                </small>
            </div>
        </aside>

        <div
            id="sidebar-backdrop"
            class="sidebar-backdrop"
        ></div>

        <div class="app-main">
            <header class="app-header">
                <div class="d-flex align-items-center gap-3">
                    <button
                        id="sidebar-toggle"
                        type="button"
                        class="btn header-menu-button"
                        aria-label="Abrir o cerrar menú"
                    >
                        <i class="bi bi-list"></i>
                    </button>

                    <div>
                        <h1 class="app-header__title">
                            @yield('page-title', 'Dashboard')
                        </h1>

                        <div class="app-header__subtitle">
                            @yield('page-subtitle', 'Resumen general del sistema')
                        </div>
                    </div>
                </div>

                <div class="dropdown">
                    <button
                        class="btn user-menu"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <span class="user-avatar">
                            {{ mb_strtoupper(mb_substr(auth()->user()->nombre_completo, 0, 1)) }}
                        </span>

                        <span class="user-menu__information">
                            <strong>
                                {{ auth()->user()->nombre_completo }}
                            </strong>

                            <small>
                                {{ auth()->user()->rol->nombre }}
                            </small>
                        </span>

                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <span class="dropdown-item-text">
                                <small class="text-muted">
                                    Sesión iniciada como
                                </small>

                                <div class="fw-semibold">
                                    {{ auth()->user()->usuario }}
                                </div>
                            </span>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="dropdown-item text-danger"
                                >
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </header>

            <main class="app-content">
                @if (session('estado'))
                    <div
                        class="alert alert-success alert-dismissible fade show"
                        role="alert"
                    >
                        {{ session('estado') }}

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Cerrar"
                        ></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>