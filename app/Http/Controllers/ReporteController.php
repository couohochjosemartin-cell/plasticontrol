<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Inventario;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(
            auth()->user()?->esAdministrador(),
            403
        );

        $fechaDesde = $request->input(
            'fecha_desde',
            now()->startOfMonth()->toDateString()
        );

        $fechaHasta = $request->input(
            'fecha_hasta',
            now()->toDateString()
        );

        $desde = Carbon::parse($fechaDesde)->startOfDay();
        $hasta = Carbon::parse($fechaHasta)->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | Ventas dentro del periodo
        |--------------------------------------------------------------------------
        */

        $ventasPeriodo = Venta::query()
            ->where('estado', 'Completada')
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

        /*
        |--------------------------------------------------------------------------
        | Ganancia estimada
        |--------------------------------------------------------------------------
        */

        $ganancia = DetalleVenta::query()
            ->whereHas(
                'venta',
                fn ($query) => $query
                    ->where('estado', 'Completada')
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
                    - (float) $detalle->producto->precio_compra;

                return $gananciaUnidad
                    * (int) $detalle->cantidad;
            });

        /*
        |--------------------------------------------------------------------------
        | Ticket promedio
        |--------------------------------------------------------------------------
        */

        $ticketPromedio = $numeroVentas > 0
            ? $ingresos / $numeroVentas
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Productos vendidos
        |--------------------------------------------------------------------------
        */

        $unidadesVendidas = (int) DetalleVenta::query()
            ->whereHas(
                'venta',
                fn ($query) => $query
                    ->where('estado', 'Completada')
                    ->whereBetween(
                        'fecha_venta',
                        [$desde, $hasta]
                    )
            )
            ->sum('cantidad');

        /*
        |--------------------------------------------------------------------------
        | Productos más vendidos
        |--------------------------------------------------------------------------
        */

        $productosMasVendidos = DetalleVenta::query()
            ->select(
                'producto_id',
                DB::raw('SUM(cantidad) as unidades'),
                DB::raw('SUM(subtotal) as importe')
            )
            ->whereHas(
                'venta',
                fn ($query) => $query
                    ->where('estado', 'Completada')
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

        /*
        |--------------------------------------------------------------------------
        | Ventas por usuario
        |--------------------------------------------------------------------------
        */

        $ventasPorUsuario = Venta::query()
            ->select(
                'usuario_id',
                DB::raw('COUNT(*) as ventas'),
                DB::raw('SUM(total) as total')
            )
            ->where('estado', 'Completada')
            ->whereBetween(
                'fecha_venta',
                [$desde, $hasta]
            )
            ->with('usuario.rol')
            ->groupBy('usuario_id')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ventas por día
        |--------------------------------------------------------------------------
        */

        $ventasPorDia = Venta::query()
            ->selectRaw(
                'DATE(fecha_venta) as fecha,
                 COUNT(*) as ventas,
                 SUM(total) as total'
            )
            ->where('estado', 'Completada')
            ->whereBetween(
                'fecha_venta',
                [$desde, $hasta]
            )
            ->groupByRaw('DATE(fecha_venta)')
            ->orderBy('fecha')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Estado actual del inventario
        |--------------------------------------------------------------------------
        */

        $inventario = [
            'disponibles' => Inventario::query()
                ->where('estado', 'Disponible')
                ->count(),

            'stock_bajo' => Inventario::query()
                ->where('estado', 'Stock bajo')
                ->count(),

            'agotados' => Inventario::query()
                ->where('estado', 'Agotado')
                ->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Ventas recientes del periodo
        |--------------------------------------------------------------------------
        */

        $ventas = Venta::query()
            ->with('usuario')
            ->where('estado', 'Completada')
            ->whereBetween(
                'fecha_venta',
                [$desde, $hasta]
            )
            ->orderByDesc('fecha_venta')
            ->paginate(10)
            ->withQueryString();

        return view(
            'reportes.index',
            compact(
                'fechaDesde',
                'fechaHasta',
                'numeroVentas',
                'ingresos',
                'descuentos',
                'ganancia',
                'ticketPromedio',
                'unidadesVendidas',
                'productosMasVendidos',
                'ventasPorUsuario',
                'ventasPorDia',
                'inventario',
                'ventas'
            )
        );
    }
}