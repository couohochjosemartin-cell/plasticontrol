<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <title>
        Reporte de PlastiControl
    </title>

    <style>
        @page {
            margin: 24px 30px 34px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #263746;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
        }

        /* ========================================
           ENCABEZADO
        ======================================== */

        .header-table {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo {
            width: 68px;
            max-height: 68px;
            object-fit: contain;
        }

        .business-name {
            margin: 0 0 3px;
            color: #174f7d;
            font-size: 21px;
            font-weight: bold;
        }

        .system-name {
            margin-bottom: 3px;
            color: #627586;
            font-size: 10px;
        }

        .business-data {
            color: #657786;
            font-size: 8px;
            line-height: 1.5;
        }

        .report-title {
            color: #174f7d;
            font-size: 17px;
            font-weight: bold;
            text-align: right;
        }

        .report-period {
            margin-top: 4px;
            color: #657786;
            text-align: right;
            line-height: 1.5;
        }

        .divider {
            margin-bottom: 14px;
            border-top: 2px solid #174f7d;
        }

        /* ========================================
           SECCIONES
        ======================================== */

        .section-title {
            margin: 15px 0 7px;
            color: #174f7d;
            font-size: 12px;
            font-weight: bold;
        }

        .section-subtitle {
            margin-top: -4px;
            margin-bottom: 7px;
            color: #788895;
            font-size: 8px;
        }

        /* ========================================
           MÉTRICAS
        ======================================== */

       .metrics {
    width: 100%;
    margin-bottom: 10px;
    border-collapse: collapse;
    table-layout: fixed;
}

.metric {
    width: 33.33%;
    padding: 8px;
    background: #f4f8fb;
    border: 1px solid #dde7ee;
    vertical-align: middle;
}

.metric-label {
    color: #6f8190;
    font-size: 8px;
}

.metric-value {
    margin-top: 3px;
    color: #174f7d;
    font-size: 14px;
    font-weight: bold;
}

        /* ========================================
           INVENTARIO
        ======================================== */

       .inventory-table {
    width: 100%;
    margin-bottom: 10px;
    border-collapse: collapse;
    table-layout: fixed;
}

        .inventory-card {
            width: 33.33%;
            padding: 8px;
            background: #f8fafc;
            border: 1px solid #e0e8ed;
            text-align: center;
        }

        .inventory-number {
            display: block;
            margin-top: 3px;
            color: #174f7d;
            font-size: 15px;
            font-weight: bold;
        }

        /* ========================================
           TABLAS
        ======================================== */

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th {
            padding: 6px;
            color: #ffffff;
            background: #1d5b88;
            border: 1px solid #1d5b88;
            font-size: 8px;
            text-align: left;
        }

        table.data td {
            padding: 5px 6px;
            border-bottom: 1px solid #dfe7ec;
            vertical-align: top;
        }

        table.data tbody tr:nth-child(even) {
            background: #f7f9fb;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .strong {
            font-weight: bold;
        }

        .muted {
            color: #71818e;
        }

        .no-data {
            padding: 15px !important;
            color: #788895;
            text-align: center;
        }

        /* ========================================
           DOS COLUMNAS
        ======================================== */

        .columns {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

        .column {
            width: 50%;
            vertical-align: top;
        }

        /* ========================================
           OPERACIONES
        ======================================== */

        .operations {
            page-break-before: auto;
        }

        /* ========================================
           FOOTER
        ======================================== */

        .footer {
    margin-top: 18px;
    padding-top: 5px;
    color: #84919b;
    border-top: 1px solid #dce5ea;
    font-size: 7px;
}

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }
    </style>
</head>

<body>

    {{-- ==========================================
         ENCABEZADO
    =========================================== --}}

    <table class="header-table">
        <tr>

            <td style="width: 10%;">

                @if ($logoBase64)
                    <img
                        src="{{ $logoBase64 }}"
                        alt="Logo"
                        class="logo"
                    >
                @endif

            </td>

            <td style="width: 50%;">

                <div class="business-name">
                    {{ $configuracion?->nombre_negocio
                        ?? 'PlastiTodo y Mas'
                    }}
                </div>

                <div class="system-name">
                    Reporte generado por PlastiControl
                </div>

                <div class="business-data">

                    @if ($configuracion?->propietario)
                        Propietario:
                        {{ $configuracion->propietario }}
                        <br>
                    @endif

                    @if ($configuracion?->rfc)
                        RFC:
                        {{ $configuracion->rfc }}
                        ·
                    @endif

                    @if ($configuracion?->telefono)
                        Tel:
                        {{ $configuracion->telefono }}
                        <br>
                    @endif

                    @if ($configuracion?->direccion)
                        {{ $configuracion->direccion }}
                    @endif

                </div>

            </td>

            <td style="width: 40%;">

                <div class="report-title">
                    Reporte comercial
                </div>

                <div class="report-period">
                    Periodo:
                    <strong>
                        {{ \Carbon\Carbon::parse(
                            $fechaDesde
                        )->format('d/m/Y') }}
                    </strong>

                    al

                    <strong>
                        {{ \Carbon\Carbon::parse(
                            $fechaHasta
                        )->format('d/m/Y') }}
                    </strong>

                    <br>

                    Generado:
                    {{ now()->format(
                        'd/m/Y H:i'
                    ) }}
                </div>

            </td>

        </tr>
    </table>

    <div class="divider"></div>

    {{-- ==========================================
         RESUMEN GENERAL
    =========================================== --}}

    <div class="section-title">
        Resumen general
    </div>

    
        <table class="metrics">

    <tr>
        <td class="metric">
            <div class="metric-label">
                Ingresos
            </div>

            <div class="metric-value">
                ${{ number_format(
                    (float) $ingresos,
                    2
                ) }}
            </div>
        </td>

        <td class="metric">
            <div class="metric-label">
                Ganancia estimada
            </div>

            <div class="metric-value">
                ${{ number_format(
                    (float) $ganancia,
                    2
                ) }}
            </div>
        </td>

        <td class="metric">
            <div class="metric-label">
                Ventas realizadas
            </div>

            <div class="metric-value">
                {{ $numeroVentas }}
            </div>
        </td>
    </tr>

    <tr>
        <td class="metric">
            <div class="metric-label">
                Ticket promedio
            </div>

            <div class="metric-value">
                ${{ number_format(
                    (float) $ticketPromedio,
                    2
                ) }}
            </div>
        </td>

        <td class="metric">
            <div class="metric-label">
                Unidades vendidas
            </div>

            <div class="metric-value">
                {{ $unidadesVendidas }}
            </div>
        </td>

        <td class="metric">
            <div class="metric-label">
                Descuentos
            </div>

            <div class="metric-value">
                ${{ number_format(
                    (float) $descuentos,
                    2
                ) }}
            </div>
        </td>
    </tr>

</table>

    {{-- ==========================================
         INVENTARIO
    =========================================== --}}

    <div class="section-title">
        Estado actual del inventario
    </div>

    <table class="inventory-table">
        <tr>

            <td class="inventory-card">
                Productos disponibles

                <span class="inventory-number">
                    {{ $inventario['disponibles'] }}
                </span>
            </td>

            <td class="inventory-card">
                Productos con stock bajo

                <span class="inventory-number">
                    {{ $inventario['stock_bajo'] }}
                </span>
            </td>

            <td class="inventory-card">
                Productos agotados

                <span class="inventory-number">
                    {{ $inventario['agotados'] }}
                </span>
            </td>

        </tr>
    </table>

    {{-- ==========================================
         VENTAS DIARIAS + PRODUCTOS
    =========================================== --}}

    <table class="columns">
        <tr>

            <td class="column">

                <div class="section-title">
                    Ventas por día
                </div>

                <table class="data">
                    <thead>
                        <tr>
                            <th>Fecha</th>

                            <th class="text-center">
                                Ventas
                            </th>

                            <th class="text-right">
                                Ingresos
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($ventasPorDia as $dia)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse(
                                        $dia->fecha
                                    )->format('d/m/Y') }}
                                </td>

                                <td class="text-center">
                                    {{ $dia->ventas }}
                                </td>

                                <td class="text-right">
                                    ${{ number_format(
                                        (float) $dia->total,
                                        2
                                    ) }}
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td
                                    colspan="3"
                                    class="no-data"
                                >
                                    Sin ventas en el periodo.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

            </td>

            <td class="column">

                <div class="section-title">
                    Productos más vendidos
                </div>

                <table class="data">
                    <thead>
                        <tr>
                            <th>Producto</th>

                            <th class="text-center">
                                Unidades
                            </th>

                            <th class="text-right">
                                Importe
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse (
                            $productosMasVendidos as $registro
                        )
                            <tr>
                                <td>
                                    <span class="strong">
                                        {{ $registro
                                            ->producto?->nombre
                                            ?? 'Producto eliminado'
                                        }}
                                    </span>

                                    @if (
                                        $registro->producto?->codigo
                                    )
                                        <br>

                                        <span class="muted">
                                            {{ $registro
                                                ->producto
                                                ->codigo
                                            }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    {{ $registro->unidades }}
                                </td>

                                <td class="text-right">
                                    ${{ number_format(
                                        (float) $registro->importe,
                                        2
                                    ) }}
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td
                                    colspan="3"
                                    class="no-data"
                                >
                                    Sin productos vendidos.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

            </td>

        </tr>
    </table>

    {{-- ==========================================
         VENTAS POR USUARIO
    =========================================== --}}

    <div class="section-title">
        Ventas por usuario
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Rol</th>

                <th class="text-center">
                    Ventas
                </th>

                <th class="text-right">
                    Total vendido
                </th>
            </tr>
        </thead>

        <tbody>

            @forelse ($ventasPorUsuario as $registro)

                <tr>
                    <td>
                        {{ $registro
                            ->usuario?->nombre_completo
                            ?? 'Usuario eliminado'
                        }}
                    </td>

                    <td>
                        {{ $registro
                            ->usuario?->rol?->nombre
                            ?? 'Sin rol'
                        }}
                    </td>

                    <td class="text-center">
                        {{ $registro->ventas }}
                    </td>

                    <td class="text-right">
                        ${{ number_format(
                            (float) $registro->total,
                            2
                        ) }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td
                        colspan="4"
                        class="no-data"
                    >
                        Sin ventas registradas.
                    </td>
                </tr>

            @endforelse

        </tbody>
    </table>

    {{-- ==========================================
         OPERACIONES
    =========================================== --}}

    <div class="section-title operations">
        Operaciones del periodo
    </div>

    <div class="section-subtitle">
        Detalle completo de las ventas incluidas
        en el reporte.
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Método</th>

                <th class="text-right">
                    Subtotal
                </th>

                <th class="text-right">
                    Descuento
                </th>

                <th class="text-right">
                    Total
                </th>
            </tr>
        </thead>

        <tbody>

            @forelse ($ventas as $venta)

                <tr>
                    <td>
                        {{ $venta->folio }}
                    </td>

                    <td>
                        {{ $venta
                            ->fecha_venta
                            ->format('d/m/Y H:i')
                        }}
                    </td>

                    <td>
                        {{ $venta
                            ->usuario?->nombre_completo
                            ?? 'Usuario eliminado'
                        }}
                    </td>

                    <td>
                        {{ $venta
                            ->metodo_pago
                            ->value
                        }}
                    </td>

                    <td class="text-right">
                        ${{ number_format(
                            (float) $venta->subtotal,
                            2
                        ) }}
                    </td>

                    <td class="text-right">
                        ${{ number_format(
                            (float) $venta->descuento,
                            2
                        ) }}
                    </td>

                    <td class="text-right strong">
                        ${{ number_format(
                            (float) $venta->total,
                            2
                        ) }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td
                        colspan="7"
                        class="no-data"
                    >
                        No existen operaciones
                        en el periodo seleccionado.
                    </td>
                </tr>

            @endforelse

        </tbody>
    </table>

    {{-- ==========================================
         FOOTER
    =========================================== --}}

    <div class="footer">

        <span class="footer-left">
            {{ $configuracion?->nombre_negocio
                ?? 'PlastiTodo y Mas'
            }}
            · PlastiControl
        </span>

        <span class="footer-right">
            Reporte generado el
            {{ now()->format('d/m/Y H:i') }}
        </span>

    </div>

</body>
</html>