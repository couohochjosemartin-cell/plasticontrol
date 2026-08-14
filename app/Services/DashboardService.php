<?php

namespace App\Services;

use App\Enums\EstadoInventario;
use App\Enums\EstadoVenta;
use App\Models\DetalleVenta;
use App\Models\Inventario;
use App\Models\Venta;
use Carbon\Carbon;

class DashboardService
{
    public function obtenerDatos(): array
    {
        $hoy = Carbon::today();

        return [
            'ventasHoy' => $this->ventasHoy($hoy),

            'gananciaHoy' => $this->gananciaHoy($hoy),

            'productosStockBajo' =>
                $this->productosStockBajo(),

            'productoMasVendido' =>
                $this->productoMasVendido(),

            'ventasRecientes' =>
                $this->ventasRecientes(),

            'ventasPorMes' =>
                $this->ventasPorMes(),

            'ventasSemana' =>
                $this->ventasSemana(),
        ];
    }

    private function ventasHoy(
        Carbon $hoy
    ): float {
        return (float) Venta::query()
            ->where(
                'estado',
                EstadoVenta::COMPLETADA->value
            )
            ->whereDate(
                'fecha_venta',
                $hoy
            )
            ->sum('total');
    }

    private function gananciaHoy(
        Carbon $hoy
    ): float {
        return (float) DetalleVenta::query()
            ->whereHas(
                'venta',
                function ($query) use ($hoy): void {
                    $query
                        ->where(
                            'estado',
                            EstadoVenta::COMPLETADA->value
                        )
                        ->whereDate(
                            'fecha_venta',
                            $hoy
                        );
                }
            )
            ->with('producto')
            ->get()
            ->sum(
                function ($detalle): float {
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
                }
            );
    }

    private function productosStockBajo(): int
    {
        return Inventario::query()
            ->where(
                'estado',
                EstadoInventario::STOCK_BAJO->value
            )
            ->count();
    }

    private function productoMasVendido()
    {
        return DetalleVenta::query()
            ->selectRaw(
                'producto_id, SUM(cantidad) as total_vendido'
            )
            ->whereHas(
                'venta',
                function ($query): void {
                    $query->where(
                        'estado',
                        EstadoVenta::COMPLETADA->value
                    );
                }
            )
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->with('producto')
            ->first();
    }

    private function ventasRecientes()
    {
        return Venta::query()
            ->with('usuario')
            ->where(
                'estado',
                EstadoVenta::COMPLETADA->value
            )
            ->orderByDesc('fecha_venta')
            ->limit(5)
            ->get();
    }

    private function ventasPorMes()
    {
        $ventasPorMes = collect();

        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()
                ->copy()
                ->subMonths($i);

            $total = Venta::query()
                ->where(
                    'estado',
                    EstadoVenta::COMPLETADA->value
                )
                ->whereYear(
                    'fecha_venta',
                    $fecha->year
                )
                ->whereMonth(
                    'fecha_venta',
                    $fecha->month
                )
                ->sum('total');

            $ventasPorMes->push([
                'mes' => ucfirst(
                    $fecha
                        ->locale('es')
                        ->translatedFormat('M')
                ),

                'total' => (float) $total,
            ]);
        }

        return $ventasPorMes;
    }

    private function ventasSemana()
    {
        $inicioSemana = now()
            ->startOfWeek(Carbon::MONDAY);

        $ventasSemana = collect();

        for ($i = 0; $i < 7; $i++) {
            $fecha = $inicioSemana
                ->copy()
                ->addDays($i);

            $total = Venta::query()
                ->where(
                    'estado',
                    EstadoVenta::COMPLETADA->value
                )
                ->whereDate(
                    'fecha_venta',
                    $fecha
                )
                ->sum('total');

            $ventasSemana->push([
                'dia' => ucfirst(
                    $fecha
                        ->locale('es')
                        ->translatedFormat('D')
                ),

                'fecha' => $fecha->format('d/m'),

                'total' => (float) $total,
            ]);
        }

        return $ventasSemana;
    }
}