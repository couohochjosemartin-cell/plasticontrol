@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
<div class="container-fluid px-0">

    {{-- Encabezado --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-1">Configuración</h1>
        <p class="text-muted mb-0">
            Administra la información y preferencias generales de PlastiControl
        </p>
    </div>

    {{-- Mensaje de éxito --}}
    @if (session('estado'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('estado') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Cerrar"
            ></button>
        </div>
    @endif

    {{-- Errores --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-2">
                Revisa la información ingresada:
            </div>

            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('configuracion.update') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- COLUMNA PRINCIPAL --}}
            <div class="col-xl-8">

                {{-- Información del negocio --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="config-icon">
                                <i class="bi bi-shop"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Información del negocio
                                </h5>

                                <p class="text-muted mb-0">
                                    Datos principales que identifican tu negocio.
                                </p>
                            </div>
                        </div>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label
                                    for="nombre_negocio"
                                    class="form-label fw-semibold"
                                >
                                    Nombre del negocio
                                </label>

                                <input
                                    type="text"
                                    id="nombre_negocio"
                                    name="nombre_negocio"
                                    class="form-control"
                                    value="{{ old(
                                        'nombre_negocio',
                                        $configuracion->nombre_negocio
                                    ) }}"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label
                                    for="propietario"
                                    class="form-label fw-semibold"
                                >
                                    Propietario
                                </label>

                                <input
                                    type="text"
                                    id="propietario"
                                    name="propietario"
                                    class="form-control"
                                    value="{{ old(
                                        'propietario',
                                        $configuracion->propietario
                                    ) }}"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label
                                    for="rfc"
                                    class="form-label fw-semibold"
                                >
                                    RFC
                                </label>

                                <input
                                    type="text"
                                    id="rfc"
                                    name="rfc"
                                    class="form-control"
                                    value="{{ old(
                                        'rfc',
                                        $configuracion->rfc
                                    ) }}"
                                    placeholder="RFC del negocio"
                                >
                            </div>

                            <div class="col-md-6">
                                <label
                                    for="telefono"
                                    class="form-label fw-semibold"
                                >
                                    Teléfono
                                </label>

                                <input
                                    type="text"
                                    id="telefono"
                                    name="telefono"
                                    class="form-control"
                                    value="{{ old(
                                        'telefono',
                                        $configuracion->telefono
                                    ) }}"
                                    placeholder="Teléfono de contacto"
                                >
                            </div>

                            <div class="col-12">
                                <label
                                    for="direccion"
                                    class="form-label fw-semibold"
                                >
                                    Dirección
                                </label>

                                <textarea
                                    id="direccion"
                                    name="direccion"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Dirección del negocio"
                                >{{ old(
                                    'direccion',
                                    $configuracion->direccion
                                ) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Preferencias --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="config-icon">
                                <i class="bi bi-sliders"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Preferencias del sistema
                                </h5>

                                <p class="text-muted mb-0">
                                    Configuración utilizada en las operaciones
                                    comerciales.
                                </p>
                            </div>
                        </div>

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label
                                    for="moneda"
                                    class="form-label fw-semibold"
                                >
                                    Moneda
                                </label>

                                <select
                                    id="moneda"
                                    name="moneda"
                                    class="form-select"
                                    required
                                >
                                    <option
                                        value="MXN"
                                        @selected(
                                            old(
                                                'moneda',
                                                $configuracion->moneda
                                            ) === 'MXN'
                                        )
                                    >
                                        MXN - Peso mexicano
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label
                                    for="iva"
                                    class="form-label fw-semibold"
                                >
                                    IVA (%)
                                </label>

                                <div class="input-group">
                                    <input
                                        type="number"
                                        id="iva"
                                        name="iva"
                                        class="form-control"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        value="{{ old(
                                            'iva',
                                            $configuracion->iva
                                        ) }}"
                                        required
                                    >

                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label
                                    for="zona_horaria"
                                    class="form-label fw-semibold"
                                >
                                    Zona horaria
                                </label>

                                <select
                                    id="zona_horaria"
                                    name="zona_horaria"
                                    class="form-select"
                                    required
                                >
                                    <option
                                        value="America/Mexico_City"
                                        @selected(
                                            old(
                                                'zona_horaria',
                                                $configuracion->zona_horaria
                                            ) === 'America/Mexico_City'
                                        )
                                    >
                                        America/Mexico_City
                                    </option>
                                </select>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

            {{-- COLUMNA LATERAL --}}
            <div class="col-xl-4">

                {{-- Logo --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-1">
                            Logo del negocio
                        </h5>

                        <p class="text-muted mb-4">
                            Imagen representativa del establecimiento.
                        </p>

                        <div class="text-center mb-4">

                            @if ($configuracion->logo)
                                <div class="config-logo-preview">
    <img
        src="{{ asset('storage/' . $configuracion->logo) }}"
        alt="Logo del negocio"
        class="config-logo-image"
    >
</div>
                            @else
                                <div class="config-logo-placeholder mx-auto">
                                    <i class="bi bi-shop"></i>
                                </div>

                                <p class="text-muted small mt-2 mb-0">
                                    Sin logo registrado
                                </p>
                            @endif

                        </div>

                        <label
                            for="logo"
                            class="form-label fw-semibold"
                        >
                            Seleccionar imagen
                        </label>

                        <input
                            type="file"
                            id="logo"
                            name="logo"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <div class="form-text">
                            JPG, PNG o WEBP. Máximo 2 MB.
                        </div>

                        @if ($configuracion->logo)
                            <div class="form-check mt-3">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    value="1"
                                    id="eliminar_logo"
                                    name="eliminar_logo"
                                >

                                <label
                                    class="form-check-label"
                                    for="eliminar_logo"
                                >
                                    Eliminar logo actual
                                </label>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Información del sistema --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="config-icon">
                                <i class="bi bi-info-circle"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Información del sistema
                                </h5>

                                <p class="text-muted mb-0">
                                    Datos técnicos de PlastiControl.
                                </p>
                            </div>
                        </div>

                        <div class="config-info-row">
                            <span>Aplicación</span>
                            <strong>PlastiControl</strong>
                        </div>

                        <div class="config-info-row">
                            <span>Versión</span>
                            <strong>
                                {{ $configuracion->version }}
                            </strong>
                        </div>

                        <div class="config-info-row">
                            <span>Desarrollador</span>
                            <strong class="text-end">
                                {{ $configuracion->desarrollador }}
                            </strong>
                        </div>

                        <div class="config-info-row border-0 pb-0">
                            <span>Última actualización</span>

                            <strong>
                                {{ $configuracion->ultima_actualizacion
                                    ? $configuracion
                                        ->ultima_actualizacion
                                        ->format('d/m/Y')
                                    : 'Sin registro'
                                }}
                            </strong>
                        </div>

                    </div>
                </div>

            </div>

        </div>

        {{-- Guardar --}}
        <div
            class="d-flex justify-content-end align-items-center
                   gap-3 mt-4 mb-4"
        >
            <a
                href="{{ route('dashboard') }}"
                class="btn btn-outline-secondary px-4"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="btn btn-primary px-4"
            >
                <i class="bi bi-floppy me-2"></i>
                Guardar configuración
            </button>
        </div>

    </form>

</div>
@endsection

@push('styles')
<style>
    .config-icon {
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eaf4fb;
        color: #1268a8;
        font-size: 1.25rem;
    }

    .config-logo {
        width: 140px;
        height: 140px;
        object-fit: contain;
        border: 1px solid #dee2e6;
        border-radius: 18px;
        padding: 12px;
        background: #fff;
    }

    .config-logo-placeholder {
        width: 140px;
        height: 140px;
        border-radius: 18px;
        background: #edf5fa;
        color: #1268a8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }

    .config-info-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 14px 0;
        border-bottom: 1px solid #edf0f2;
    }

    .config-info-row span {
        color: #6c757d;
    }

    .config-info-row strong {
        color: #17324d;
    }
</style>
@endpush