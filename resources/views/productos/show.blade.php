@extends('layouts.app')

@section('title', 'Detalle de producto')
@section('page-title', 'Detalle de producto')
@section('page-subtitle', 'Información comercial e inventario actual')

@section('content')
    <div class="module-container">
        <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
            <a
                href="{{ route('productos.index') }}"
                class="btn btn-light border"
            >
                <i class="bi bi-arrow-left me-2"></i>
                Volver
            </a>

            @can('update', $producto)
                <a
                    href="{{ route('productos.edit', $producto) }}"
                    class="btn btn-primary"
                >
                    <i class="bi bi-pencil me-2"></i>
                    Editar producto
                </a>
            @endcan
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-4">
                <section class="module-card h-100">
                    <div class="module-card__body text-center">
                        @if ($producto->imagen)
                            <img
                                src="{{ asset('storage/' . $producto->imagen) }}"
                                alt="{{ $producto->nombre }}"
                                class="product-detail-image"
                            >
                        @else
                            <div class="product-image-placeholder">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        @endif

                        <h2 class="h4 mt-4 mb-1">
                            {{ $producto->nombre }}
                        </h2>

                        <div class="text-muted">
                            {{ $producto->codigo }}
                        </div>

                        <div class="mt-3">
                            @if (  $producto->estado === \App\Enums\EstadoProducto::ACTIVO)
                                <span class="badge text-bg-success">
                                    Activo
                                </span>
                            @else
                                <span class="badge text-bg-secondary">
                                    Inactivo
                                </span>
                            @endif
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-8">
                <section class="module-card">
                    <div class="module-card__header">
                        <div>
                            <h2 class="module-card__title">
                                Información general
                            </h2>

                            <p class="module-card__subtitle">
                                Datos comerciales y existencias del producto.
                            </p>
                        </div>
                    </div>

                    <div class="module-card__body">
                        <div class="row g-4">
                            <div class="col-12 col-md-6">
                                <div class="detail-item">
                                    <span class="detail-item__label">
                                        Categoría
                                    </span>

                                    <strong class="detail-item__value">
                                        {{ $producto->categoria->nombre }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="detail-item">
                                    <span class="detail-item__label">
                                        Compra
                                    </span>

                                    <strong class="detail-item__value">
                                        ${{ number_format((float) $producto->precio_compra, 2) }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="detail-item">
                                    <span class="detail-item__label">
                                        Venta
                                    </span>

                                    <strong class="detail-item__value">
                                        ${{ number_format((float) $producto->precio_venta, 2) }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="detail-item">
                                    <span class="detail-item__label">
                                        Stock actual
                                    </span>

                                    <strong class="detail-item__value">
                                        {{ $producto->inventario?->stock_actual ?? 0 }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="detail-item">
                                    <span class="detail-item__label">
                                        Stock mínimo
                                    </span>

                                    <strong class="detail-item__value">
                                        {{ $producto->inventario?->stock_minimo ?? 0 }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="detail-item">
                                    <span class="detail-item__label">
                                        Estado inventario
                                    </span>

                                    <strong class="detail-item__value">
                                        {{ $producto->inventario?->estado ?? 'Sin inventario' }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="detail-item">
                                    <span class="detail-item__label">
                                        Descripción
                                    </span>

                                    <div class="detail-item__value">
                                        {{ $producto->descripcion ?: 'Sin descripción registrada.' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection