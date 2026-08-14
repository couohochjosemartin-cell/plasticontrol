<?php

namespace App\Services;

use App\Enums\EstadoInventario;
use App\Enums\EstadoVenta;
use App\Models\DetalleVenta;
use App\Models\Inventario;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function obtenerReporte(
    string $fechaDesde,
    string $fechaHasta,
    bool $paginarVentas = true
): array {
        $desde = Carbon::parse($fechaDesde)->startOfDay();
        $hasta = Carbon::parse($fechaHasta)->endOfDay();

        $ventasPeriodo = Venta::query()
            ->where(
                'estado',
                EstadoVenta::COMPLETADA->value
            )
            ->whereBetween(
                'fecha_venta',
                [$desde, $hasta]
            );

        $numeroVentas = (clone $ventasPeriodo)->count();

        $ingresos = (float) (
            clone $ventasPeriodo
        )->sum('total');

        $descuentos = (float) (
            clone $ventasPeriodo
        )->sum('descuento');

        $ganancia = $this->ganancia(
            $desde,
            $hasta
        );

        $ticketPromedio = $numeroVentas > 0
            ? $ingresos / $numeroVentas
            : 0;

        return [
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,

            'numeroVentas' => $numeroVentas,
            'ingresos' => $ingresos,
            'descuentos' => $descuentos,
            'ganancia' => $ganancia,
            'ticketPromedio' => $ticketPromedio,

            'unidadesVendidas' =>
                $this->unidadesVendidas(
                    $desde,
                    $hasta
                ),

            'productosMasVendidos' =>
                $this->productosMasVendidos(
                    $desde,
                    $hasta
                ),

            'ventasPorUsuario' =>
                $this->ventasPorUsuario(
                    $desde,
                    $hasta
                ),

            'ventasPorDia' =>
                $this->ventasPorDia(
                    $desde,
                    $hasta
                ),

            'inventario' =>
                $this->resumenInventario(),

            'ventas' =>
    $this->ventas(
        $desde,
        $hasta,
        $paginarVentas
    ),
        ];
    }

    private function ganancia(
        Carbon $desde,
        Carbon $hasta
    ): float {
        return (float) DetalleVenta::query()
            ->whereHas(
                'venta',
                fn ($query) => $query
                    ->where(
                        'estado',
                        EstadoVenta::COMPLETADA->value
                    )
                    ->whereBetween(
                        'fecha_venta',
                        [$desde, $hasta]
                    )
            )
            ->with('producto')
            ->get()
            ->sum(function ($detalle): float {
                if (! $detalle->producto) {
                    return 0;
                }

                $gananciaUnidad =
                    (float) $detalle->precio_unitario
                    - (float) $detalle
                        ->producto
                        ->precio_compra;

                return $gananciaUnidad
                    * (int) $detalle->cantidad;
            });
    }

    private function unidadesVendidas(
        Carbon $desde,
        Carbon $hasta
    ): int {
        return (int) DetalleVenta::query()
            ->whereHas(
                'venta',
                fn ($query) => $query
                    ->where(
                        'estado',
                        EstadoVenta::COMPLETADA->value
                    )
                    ->whereBetween(
                        'fecha_venta',
                        [$desde, $hasta]
                    )
            )
            ->sum('cantidad');
    }

    private function productosMasVendidos(
        Carbon $desde,
        Carbon $hasta
    ) {
        return DetalleVenta::query()
            ->select(
                'producto_id',
                DB::raw(
                    'SUM(cantidad) as unidades'
                ),
                DB::raw(
                    'SUM(subtotal) as importe'
                )
            )
            ->whereHas(
                'venta',
                fn ($query) => $query
                    ->where(
                        'estado',
                        EstadoVenta::COMPLETADA->value
                    )
                    ->whereBetween(
                        'fecha_venta',
                        [$desde, $hasta]
                    )
            )
            ->with('producto')
            ->groupBy('producto_id')
            ->orderByDesc('unidades')
            ->limit(10)
            ->get();
    }

    private function ventasPorUsuario(
        Carbon $desde,
        Carbon $hasta
    ) {
        return Venta::query()
            ->select(
                'usuario_id',
                DB::raw(
                    'COUNT(*) as ventas'
                ),
                DB::raw(
                    'SUM(total) as total'
                )
            )
            ->where(
                'estado',
                EstadoVenta::COMPLETADA->value
            )
            ->whereBetween(
                'fecha_venta',
                [$desde, $hasta]
            )
            ->with('usuario.rol')
            ->groupBy('usuario_id')
            ->orderByDesc('total')
            ->get();
    }

    private function ventasPorDia(
        Carbon $desde,
        Carbon $hasta
    ) {
        return Venta::query()
            ->selectRaw(
                'DATE(fecha_venta) as fecha,
                 COUNT(*) as ventas,
                 SUM(total) as total'
            )
            ->where(
                'estado',
                EstadoVenta::COMPLETADA->value
            )
            ->whereBetween(
                'fecha_venta',
                [$desde, $hasta]
            )
            ->groupByRaw(
                'DATE(fecha_venta)'
            )
            ->orderBy('fecha')
            ->get();
    }

    private function resumenInventario(): array
    {
        return [
            'disponibles' => Inventario::query()
                ->where(
                    'estado',
                    EstadoInventario::DISPONIBLE->value
                )
                ->count(),

            'stock_bajo' => Inventario::query()
                ->where(
                    'estado',
                    EstadoInventario::STOCK_BAJO->value
                )
                ->count(),

            'agotados' => Inventario::query()
                ->where(
                    'estado',
                    EstadoInventario::AGOTADO->value
                )
                ->count(),
        ];
    }

   private function ventas(
    Carbon $desde,
    Carbon $hasta,
    bool $paginar
) {
    $query = Venta::query()
        ->with('usuario')
        ->where(
            'estado',
            EstadoVenta::COMPLETADA->value
        )
        ->whereBetween(
            'fecha_venta',
            [$desde, $hasta]
        )
        ->orderByDesc('fecha_venta');

    if ($paginar) {
        return $query
            ->paginate(10)
            ->withQueryString();
    }

    return $query->get();
}
}
