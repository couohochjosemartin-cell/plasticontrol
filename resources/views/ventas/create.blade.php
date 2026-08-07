@extends('layouts.app')

@section('title', 'Punto de Venta')
@section('page-title', 'Punto de Venta')
@section('page-subtitle', 'Registra una nueva venta')

@section('content')
    <div class="pos-container">

        <div class="d-flex justify-content-end mb-3">
            <a
                href="{{ route('ventas.index') }}"
                class="btn btn-light border"
            >
                <i class="bi bi-clock-history me-2"></i>
                Historial de ventas
            </a>
        </div>
        @if ($errors->any())
            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >
                <i class="bi bi-exclamation-triangle me-2"></i>

                <strong>
                    No se pudo completar la venta.
                </strong>

                <div class="mt-1">
                    {{ $errors->first() }}
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Cerrar"
                ></button>
            </div>
        @endif

        <form
            id="venta-form"
            method="POST"
            action="{{ route('ventas.store') }}"
        >
            @csrf

            <div class="row g-4">
                <div class="col-12 col-xl-7">
                    <section class="module-card h-100">
                        <div class="module-card__header">
                            <div>
                                <h2 class="module-card__title">
                                    Productos
                                </h2>

                                <p class="module-card__subtitle mb-0">
                                    Busca y agrega productos a la venta.
                                </p>
                            </div>

                            <div class="module-card__icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>

                        <div class="module-card__body border-bottom">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input
                                    id="pos-search"
                                    type="search"
                                    class="form-control"
                                    placeholder="Buscar por código, producto o categoría..."
                                    autocomplete="off"
                                >
                            </div>
                        </div>

                        <div
                            id="pos-products"
                            class="pos-product-grid"
                        >
                            @forelse ($productos as $producto)
                                <button
                                    type="button"
                                    class="pos-product-card"
                                    data-product-id="{{ $producto->id }}"
                                    data-product-code="{{ $producto->codigo }}"
                                    data-product-name="{{ $producto->nombre }}"
                                    data-product-category="{{ $producto->categoria->nombre }}"
                                    data-product-price="{{ $producto->precio_venta }}"
                                    data-product-stock="{{ $producto->inventario->stock_actual }}"
                                >
                                    <div class="pos-product-card__icon">
                                        @if ($producto->imagen)
                                            <img
                                                src="{{ asset('storage/' . $producto->imagen) }}"
                                                alt="{{ $producto->nombre }}"
                                            >
                                        @else
                                            <i class="bi bi-box"></i>
                                        @endif
                                    </div>

                                    <div class="pos-product-card__content">
                                        <strong>
                                            {{ $producto->nombre }}
                                        </strong>

                                        <span>
                                            {{ $producto->codigo }}
                                        </span>

                                        <small>
                                            {{ $producto->categoria->nombre }}
                                        </small>
                                    </div>

                                    <div class="pos-product-card__meta">
                                        <strong>
                                            ${{ number_format(
                                                (float) $producto->precio_venta,
                                                2
                                            ) }}
                                        </strong>

                                        <small>
                                            Stock:
                                            {{ $producto->inventario->stock_actual }}
                                        </small>
                                    </div>
                                </button>
                            @empty
                                <div class="empty-state">
                                    <i class="bi bi-cart-x"></i>

                                    <strong class="d-block">
                                        No hay productos disponibles
                                    </strong>

                                    <span>
                                        Registra productos con existencias para comenzar a vender.
                                    </span>
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>

                <div class="col-12 col-xl-5">
                    <section class="module-card pos-ticket">
                        <div class="module-card__header">
                            <div>
                                <h2 class="module-card__title">
                                    Venta actual
                                </h2>

                                <p class="module-card__subtitle mb-0">
                                    Productos agregados al ticket.
                                </p>
                            </div>

                            <div class="module-card__icon">
                                <i class="bi bi-receipt"></i>
                            </div>
                        </div>

                        <div
                            id="pos-cart"
                            class="pos-cart"
                        >
                            <div
                                id="pos-empty-cart"
                                class="empty-state py-5"
                            >
                                <i class="bi bi-cart3"></i>

                                <strong class="d-block">
                                    Venta vacía
                                </strong>

                                <span>
                                    Selecciona productos para agregarlos.
                                </span>
                            </div>
                        </div>

                        <div class="pos-summary">
                            <div class="pos-summary__row">
                                <span>Subtotal</span>

                                <strong id="pos-subtotal">
                                    $0.00
                                </strong>
                            </div>

                            <div class="mb-3">
                                <label
                                    for="descuento"
                                    class="form-label"
                                >
                                    Descuento
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input
                                        id="descuento"
                                        name="descuento"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        class="form-control"
                                        value="{{ old('descuento', 0) }}"
                                    >
                                </div>
                            </div>

                            <div class="pos-summary__total">
                                <span>Total</span>

                                <strong id="pos-total">
                                    $0.00
                                </strong>
                            </div>

                            <div class="mb-3">
                                <label
                                    for="pago_recibido"
                                    class="form-label"
                                >
                                    Efectivo recibido
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input
                                        id="pago_recibido"
                                        name="pago_recibido"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        class="form-control"
                                        value="{{ old('pago_recibido') }}"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="pos-summary__change">
                                <span>Cambio</span>

                                <strong id="pos-change">
                                    $0.00
                                </strong>
                            </div>

                            <button
                                id="pos-submit"
                                type="submit"
                                class="btn btn-primary btn-lg w-100 mt-4"
                                disabled
                            >
                                <i class="bi bi-cash-coin me-2"></i>
                                Cobrar
                            </button>
                        </div>
                    </section>
                </div>
            </div>
        </form>
    </div>
@endsection