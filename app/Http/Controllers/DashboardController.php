<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Inventario;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hoy = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | Ventas del día
        |--------------------------------------------------------------------------
        */

        $ventasHoy = Venta::query()
            ->where('estado', 'Completada')
            ->whereDate('fecha_venta', $hoy)
            ->sum('total');

        /*
        |--------------------------------------------------------------------------
        | Ganancia del día
        |--------------------------------------------------------------------------
        | Ganancia = precio de venta - precio de compra
        */

        $gananciaHoy = DetalleVenta::query()
            ->whereHas('venta', function ($query) use ($hoy) {
                $query->where('estado', 'Completada')
                    ->whereDate('fecha_venta', $hoy);
            })
            ->with('producto')
            ->get()
            ->sum(function ($detalle) {
                if (!$detalle->producto) {
                    return 0;
                }

                $gananciaUnidad =
                    (float) $detalle->precio_unitario
                    - (float) $detalle->producto->precio_compra;

                return $gananciaUnidad * $detalle->cantidad;
            });

        /*
        |--------------------------------------------------------------------------
        | Productos con stock bajo
        |--------------------------------------------------------------------------
        */

        $productosStockBajo = Inventario::query()
            ->where('estado', 'Stock bajo')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Producto más vendido
        |--------------------------------------------------------------------------
        */

        $productoMasVendido = DetalleVenta::query()
            ->selectRaw(
                'producto_id, SUM(cantidad) as total_vendido'
            )
            ->whereHas('venta', function ($query) {
                $query->where('estado', 'Completada');
            })
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->with('producto')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Ventas recientes
        |--------------------------------------------------------------------------
        */

        $ventasRecientes = Venta::query()
            ->with('usuario')
            ->where('estado', 'Completada')
            ->orderByDesc('fecha_venta')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ventas de los últimos 6 meses
        |--------------------------------------------------------------------------
        */

        $ventasPorMes = collect();

        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->copy()->subMonths($i);

            $total = Venta::query()
                ->where('estado', 'Completada')
                ->whereYear('fecha_venta', $fecha->year)
                ->whereMonth('fecha_venta', $fecha->month)
                ->sum('total');

            $ventasPorMes->push([
                'mes' => ucfirst(
                    $fecha->locale('es')->translatedFormat('M')
                ),
                'total' => (float) $total,
            ]);
        }

        return view('dashboard', compact(
            'ventasHoy',
            'gananciaHoy',
            'productosStockBajo',
            'productoMasVendido',
            'ventasRecientes',
            'ventasPorMes'
        ));
    }
}