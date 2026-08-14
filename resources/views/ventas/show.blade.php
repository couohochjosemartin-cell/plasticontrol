@extends('layouts.app')

@section('title', 'Venta completada')
@section('page-title', 'Venta completada')
@section('page-subtitle', 'Detalle de la operación registrada')

@section('content')
    <div class="module-container">
        @if (session('estado'))
            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >
                <i class="bi bi-check-circle me-2"></i>

                {{ session('estado') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Cerrar"
                ></button>
            </div>
        @endif

        <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">

<a
    href="{{ route('ventas.index') }}"
    class="btn btn-light border"
>
    <i class="bi bi-clock-history me-2"></i>
    Historial
</a>

            <a
                href="{{ route('ventas.create') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-2"></i>
                Nueva venta
            </a>

            <a
                href="{{ route('dashboard') }}"
                class="btn btn-light border"
            >
                <i class="bi bi-grid me-2"></i>
                Dashboard
            </a>

<a
    href="{{ route('ventas.ticket', $venta) }}"
    class="btn btn-primary"
    target="_blank"
>
    <i class="bi bi-printer me-2"></i>
    Imprimir ticket
</a>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <section class="module-card">
                    <div class="module-card__header">
                        <div>
                            <span class="badge text-bg-success mb-2">
                                {{ $venta->estado }}
                            </span>

                            <h2 class="module-card__title">
                                Venta {{ $venta->folio }}
                            </h2>

                            <p class="module-card__subtitle mb-0">
                                {{ $venta->fecha_venta->format('d/m/Y H:i:s') }}
                            </p>
                        </div>

                        <div class="module-card__icon">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table module-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th class="text-center">
                                        Cantidad
                                    </th>
                                    <th class="text-end">
                                        Precio
                                    </th>
                                    <th class="text-end">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($venta->detalles as $detalle)
                                    <tr>
                                        <td class="fw-semibold">
                                            {{ $detalle->producto->nombre }}
                                        </td>

                                        <td>
                                            {{ $detalle->producto->codigo }}
                                        </td>

                                        <td class="text-center">
                                            {{ $detalle->cantidad }}
                                        </td>

                                        <td class="text-end">
                                            ${{ number_format(
                                                (float) $detalle->precio_unitario,
                                                2
                                            ) }}
                                        </td>

                                        <td class="text-end fw-semibold">
                                            ${{ number_format(
                                                (float) $detalle->subtotal,
                                                2
                                            ) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-4">
                <section class="module-card">
                    <div class="module-card__header">
                        <div>
                            <h2 class="module-card__title">
                                Resumen
                            </h2>

                            <p class="module-card__subtitle mb-0">
                                Información del cobro
                            </p>
                        </div>
                    </div>

                    <div class="module-card__body">
                        <div class="sale-summary-row">
                            <span>Subtotal</span>

                            <strong>
                                ${{ number_format(
                                    (float) $venta->subtotal,
                                    2
                                ) }}
                            </strong>
                        </div>

                        <div class="sale-summary-row">
                            <span>Descuento</span>

                            <strong>
                                ${{ number_format(
                                    (float) $venta->descuento,
                                    2
                                ) }}
                            </strong>
                        </div>

                        <hr>

                        <div class="sale-summary-total">
                            <span>Total</span>

                            <strong>
                                ${{ number_format(
                                    (float) $venta->total,
                                    2
                                ) }}
                            </strong>
                        </div>

                        <div class="sale-summary-row mt-4">
                            <span>Efectivo recibido</span>

                            <strong>
                                ${{ number_format(
                                    (float) $venta->pago_recibido,
                                    2
                                ) }}
                            </strong>
                        </div>

                        <div class="sale-change">
                            <span>Cambio</span>

                            <strong>
                                ${{ number_format(
                                    (float) $venta->cambio,
                                    2
                                ) }}
                            </strong>
                        </div>

                        <hr>

                        <div class="small text-muted">
                            <div class="mb-2">
                                <i class="bi bi-person me-2"></i>

                                Atendió:
                                <strong>
                                    {{ $venta->usuario->nombre_completo }}
                                </strong>
                            </div>

                            <div>
                                <i class="bi bi-cash me-2"></i>

                                Método:
                                <strong>
                                    {{ $venta->metodo_pago }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection