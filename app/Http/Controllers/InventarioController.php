<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovimientoInventarioRequest;
use App\Models\Inventario;
use App\Services\InventarioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventarioController extends Controller
{
    public function __construct(
        private readonly InventarioService $inventarioService
    ) {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Inventario::class);

        $buscar = trim((string) $request->input('buscar'));
        $estado = $request->input('estado', 'todos');

        $inventarios = Inventario::query()
            ->with([
                'producto.categoria',
            ])
            ->whereHas(
                'producto',
                fn ($query) => $query
                    ->whereNull('deleted_at')
            )
            ->when(
                $buscar !== '',
                function ($query) use ($buscar): void {
                    $query->whereHas(
                        'producto',
                        function ($productoQuery) use ($buscar): void {
                            $productoQuery->where(
                                function ($subquery) use ($buscar): void {
                                    $subquery
                                        ->where(
                                            'nombre',
                                            'like',
                                            "%{$buscar}%"
                                        )
                                        ->orWhere(
                                            'codigo',
                                            'like',
                                            "%{$buscar}%"
                                        );
                                }
                            );
                        }
                    );
                }
            )
            ->when(
                in_array(
                    $estado,
                    ['Disponible', 'Stock bajo', 'Agotado'],
                    true
                ),
                fn ($query) => $query->where(
                    'estado',
                    $estado
                )
            )
            ->orderBy('estado')
            ->orderBy('stock_actual')
            ->paginate(10)
            ->withQueryString();

        $resumen = [
            'productos' => Inventario::whereHas(
                'producto',
                fn ($query) => $query->whereNull('deleted_at')
            )->count(),

            'disponibles' => Inventario::where(
                'estado',
                'Disponible'
            )->count(),

            'stock_bajo' => Inventario::where(
                'estado',
                'Stock bajo'
            )->count(),

            'agotados' => Inventario::where(
                'estado',
                'Agotado'
            )->count(),
        ];

        return view(
            'inventario.index',
            compact(
                'inventarios',
                'buscar',
                'estado',
                'resumen'
            )
        );
    }


public function show(
    Inventario $inventario
): View {
    $this->authorize('view', $inventario);

    $inventario->load([
        'producto.categoria',
    ]);

    $movimientos = $inventario
        ->movimientos()
        ->with('usuario')
        ->orderByDesc('fecha_movimiento')
        ->paginate(15);

    return view(
        'inventario.show',
        compact(
            'inventario',
            'movimientos'
        )
    );
}

    public function movimiento(
        MovimientoInventarioRequest $request,
        Inventario $inventario
    ): RedirectResponse {
        $this->authorize('update', $inventario);

        $this->inventarioService->registrarMovimiento(
            $inventario,
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('inventario.index')
            ->with(
                'estado',
                'Movimiento de inventario registrado correctamente.'
            );
    }
}