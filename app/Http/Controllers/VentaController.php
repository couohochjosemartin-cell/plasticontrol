<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVentaRequest;
use App\Models\Producto;
use App\Models\Venta;
use App\Services\VentaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function __construct(
        private readonly VentaService $ventaService
    ) {
    }

public function ticket(Venta $venta): View
{
    $this->authorize('view', $venta);

    $venta->load([
        'usuario',
        'detalles.producto',
    ]);

    $configuracion = \App\Models\Configuracion::query()
        ->first();

    return view(
        'ventas.ticket',
        compact(
            'venta',
            'configuracion'
        )
    );
}

public function index(Request $request): View
{
    $this->authorize('viewAny', Venta::class);

    $buscar = trim((string) $request->input('buscar'));
    $fechaDesde = $request->input('fecha_desde');
    $fechaHasta = $request->input('fecha_hasta');

    $ventas = Venta::query()
        ->with('usuario')
        ->withCount('detalles')
        ->when(
            $buscar !== '',
            fn ($query) => $query->where(
                'folio',
                'like',
                "%{$buscar}%"
            )
        )
        ->when(
            filled($fechaDesde),
            fn ($query) => $query->whereDate(
                'fecha_venta',
                '>=',
                $fechaDesde
            )
        )
        ->when(
            filled($fechaHasta),
            fn ($query) => $query->whereDate(
                'fecha_venta',
                '<=',
                $fechaHasta
            )
        )
        ->orderByDesc('fecha_venta')
        ->paginate(15)
        ->withQueryString();

    $resumen = [
        'ventas_hoy' => Venta::query()
            ->whereDate('fecha_venta', today())
            ->where('estado', 'Completada')
            ->count(),

        'ingresos_hoy' => Venta::query()
            ->whereDate('fecha_venta', today())
            ->where('estado', 'Completada')
            ->sum('total'),

        'ventas_totales' => Venta::query()
            ->where('estado', 'Completada')
            ->count(),

        'ingresos_totales' => Venta::query()
            ->where('estado', 'Completada')
            ->sum('total'),
    ];

    return view(
        'ventas.index',
        compact(
            'ventas',
            'buscar',
            'fechaDesde',
            'fechaHasta',
            'resumen'
        )
    );
}


    public function create(): View
    {
        $this->authorize('create', Venta::class);

        $productos = Producto::query()
            ->with([
                'categoria',
                'inventario',
            ])
            ->where('estado', 'Activo')
            ->whereHas(
                'inventario',
                fn ($query) => $query->where(
                    'stock_actual',
                    '>',
                    0
                )
            )
            ->orderBy('nombre')
            ->get();

        return view(
            'ventas.create',
            compact('productos')
        );
    }

    public function store(
        StoreVentaRequest $request
    ): RedirectResponse {
        $venta = $this->ventaService->registrar(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('ventas.show', $venta)
            ->with(
                'estado',
                'Venta registrada correctamente.'
            );
    }

    public function show(Venta $venta): View
    {
        $this->authorize('view', $venta);

        $venta->load([
            'usuario',
            'detalles.producto',
        ]);

        return view(
            'ventas.show',
            compact('venta')
        );
    }
}