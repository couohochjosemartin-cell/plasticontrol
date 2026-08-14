<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Ticket {{ $venta->folio }}
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #f3f4f6;
            color: #111827;
            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        .ticket {
            width: 80mm;
            margin: 0 auto;
            padding: 16px;
            background: white;
            border-radius: 8px;
            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .ticket-header {
            text-align: center;
        }

        .ticket-logo {
            display: block;
            width: auto;
            height: auto;
            max-width: 85px;
            max-height: 85px;
            margin: 0 auto 10px;
            object-fit: contain;
        }

        .ticket-title {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .ticket-subtitle {
            margin-top: 3px;
            color: #4b5563;
            font-size: 12px;
        }

        .separator {
            margin: 12px 0;
            border: 0;
            border-top: 1px dashed #6b7280;
        }

        .ticket-info {
            font-size: 11px;
            line-height: 1.6;
        }

        .ticket-info div {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th {
            padding-bottom: 6px;
            text-align: left;
            border-bottom: 1px solid #d1d5db;
        }

        td {
            padding: 7px 0;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .product-name {
            font-weight: 600;
        }

        .product-code {
            margin-top: 2px;
            color: #6b7280;
            font-size: 9px;
        }

        .totals {
            font-size: 12px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }

        .grand-total {
            margin-top: 5px;
            padding-top: 7px;
            border-top: 1px solid #111827;
            font-size: 15px;
            font-weight: 700;
        }

        .ticket-footer {
            margin-top: 16px;
            text-align: center;
            font-size: 10px;
            line-height: 1.5;
        }

        .ticket-actions {
            width: 80mm;
            margin: 16px auto;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .ticket-actions button,
        .ticket-actions a {
            padding: 9px 14px;
            border: 0;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-print {
            background: #0d6efd;
            color: white;
        }

        .btn-back {
            background: white;
            color: #374151;
            border: 1px solid #d1d5db !important;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 4mm;
            }

            body {
                padding: 0;
                background: white;
            }

            .ticket {
                width: 100%;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
            }

            .ticket-actions {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="ticket">

        <header class="ticket-header">

            @if ($configuracion?->logo)
                <img
                    src="{{ asset(
                        'storage/' . $configuracion->logo
                    ) }}"
                    alt="Logo"
                    class="ticket-logo"
                >
            @endif

            <h1 class="ticket-title">
                {{ $configuracion?->nombre_negocio
                    ?? 'PlastiControl'
                }}
            </h1>

            @if ($configuracion?->direccion)
                <div class="ticket-subtitle">
                    {{ $configuracion->direccion }}
                </div>
            @endif

            @if ($configuracion?->telefono)
                <div class="ticket-subtitle">
                    Tel. {{ $configuracion->telefono }}
                </div>
            @endif

            @if ($configuracion?->rfc)
                <div class="ticket-subtitle">
                    RFC: {{ $configuracion->rfc }}
                </div>
            @endif
        </header>

        <hr class="separator">

        <div class="ticket-info">
            <div>
                <span>Folio:</span>
                <strong>{{ $venta->folio }}</strong>
            </div>

            <div>
                <span>Fecha:</span>
                <span>
                    {{ $venta->fecha_venta->format(
                        'd/m/Y H:i'
                    ) }}
                </span>
            </div>

            <div>
                <span>Atendió:</span>
                <span>
                    {{ $venta->usuario?->nombre_completo
                        ?? 'Sin usuario'
                    }}
                </span>
            </div>

            <div>
                <span>Pago:</span>
                <span>
                    {{ $venta->metodo_pago->value }}
                </span>
            </div>
        </div>

        <hr class="separator">

        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-right">Importe</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($venta->detalles as $detalle)
                    <tr>
                        <td>
                            <div class="product-name">
                                {{ $detalle->producto?->nombre
                                    ?? 'Producto'
                                }}
                            </div>

                            <div class="product-code">
                                {{ $detalle->producto?->codigo }}
                                ·
                                ${{ number_format(
                                    (float) $detalle->precio_unitario,
                                    2
                                ) }}
                            </div>
                        </td>

                        <td class="text-center">
                            {{ $detalle->cantidad }}
                        </td>

                        <td class="text-right">
                            ${{ number_format(
                                (float) $detalle->subtotal,
                                2
                            ) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr class="separator">

        <div class="totals">

    <div class="total-row">
        <span>Subtotal</span>

        <span>
            ${{ number_format(
                (float) $venta->subtotal,
                2
            ) }}
        </span>
    </div>

    <div class="total-row">
        <span>Descuento</span>

        <span>
            -${{ number_format(
                (float) $venta->descuento,
                2
            ) }}
        </span>
    </div>

    <div class="total-row grand-total">
        <span>Total</span>

        <span>
            ${{ number_format(
                (float) $venta->total,
                2
            ) }}
        </span>
    </div>

    <div class="total-row">
        <span>Efectivo</span>

        <span>
            ${{ number_format(
                (float) $venta->pago_recibido,
                2
            ) }}
        </span>
    </div>

    <div class="total-row">
        <span>Cambio</span>

        <span>
            ${{ number_format(
                (float) $venta->cambio,
                2
            ) }}
        </span>
    </div>

</div>

        <hr class="separator">

        <footer class="ticket-footer">
            <strong>
                ¡Gracias por su compra!
            </strong>

            <div>
                {{ $configuracion?->nombre_negocio
                    ?? 'PlastiControl'
                }}
            </div>

            <div>
                Venta registrada en PlastiControl
            </div>
        </footer>

    </div>

    <div class="ticket-actions">
        <a
            href="{{ route('ventas.show', $venta) }}"
            class="btn-back"
        >
            Volver
        </a>

        <button
            type="button"
            class="btn-print"
            onclick="window.print()"
        >
            Imprimir ticket
        </button>
    </div>

</body>
</html>