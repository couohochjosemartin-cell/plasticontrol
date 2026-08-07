<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use App\Services\ProductoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function __construct(
        private readonly ProductoService $productoService
    ) {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Producto::class);

        $buscar = trim((string) $request->input('buscar'));
        $categoriaId = $request->input('categoria');
        $estado = $request->input('estado', 'todos');

        $productos = Producto::query()
            ->with([
                'categoria',
                'inventario',
            ])
            ->when(
                $estado === 'eliminados',
                fn ($query) => $query->onlyTrashed()
            )
            ->when(
                $estado === 'todos',
                fn ($query) => $query->withTrashed()
            )
            ->when(
                in_array($estado, ['Activo', 'Inactivo'], true),
                fn ($query) => $query->where('estado', $estado)
            )
            ->when(
                filled($categoriaId),
                fn ($query) => $query->where(
                    'categoria_id',
                    $categoriaId
                )
            )
            ->when(
                $buscar !== '',
                function ($query) use ($buscar): void {
                    $query->where(function ($subquery) use ($buscar): void {
                        $subquery
                            ->where('codigo', 'like', "%{$buscar}%")
                            ->orWhere('nombre', 'like', "%{$buscar}%")
                            ->orWhere('descripcion', 'like', "%{$buscar}%");
                    });
                }
            )
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $categorias = Categoria::query()
            ->where('estado', 'Activa')
            ->orderBy('nombre')
            ->get();

        $resumen = [
            'total' => Producto::withTrashed()->count(),
            'activos' => Producto::where('estado', 'Activo')->count(),
            'inactivos' => Producto::where('estado', 'Inactivo')->count(),
            'stock_bajo' => Producto::query()
                ->whereHas(
                    'inventario',
                    fn ($query) => $query
                        ->where('stock_actual', '>', 0)
                        ->whereColumn(
                            'stock_actual',
                            '<=',
                            'stock_minimo'
                        )
                )
                ->count(),
        ];

        return view(
            'productos.index',
            compact(
                'productos',
                'categorias',
                'buscar',
                'categoriaId',
                'estado',
                'resumen'
            )
        );
    }

    public function create(): View
    {
        $this->authorize('create', Producto::class);

        $categorias = Categoria::query()
            ->where('estado', 'Activa')
            ->orderBy('nombre')
            ->get();

        return view(
            'productos.create',
            compact('categorias')
        );
    }

    public function store(
        StoreProductoRequest $request
    ): RedirectResponse {
        $producto = $this->productoService->crear(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('productos.show', $producto)
            ->with(
                'estado',
                'Producto registrado correctamente.'
            );
    }

    public function show(Producto $producto): View
    {
        $this->authorize('view', $producto);

        $producto->load([
            'categoria',
            'inventario',
        ]);

        return view(
            'productos.show',
            compact('producto')
        );
    }

    public function edit(Producto $producto): View
    {
        $this->authorize('update', $producto);

        $categorias = Categoria::query()
            ->where('estado', 'Activa')
            ->orWhere('id', $producto->categoria_id)
            ->orderBy('nombre')
            ->get();

        return view(
            'productos.edit',
            compact(
                'producto',
                'categorias'
            )
        );
    }

    public function update(
        UpdateProductoRequest $request,
        Producto $producto
    ): RedirectResponse {
        $producto = $this->productoService->actualizar(
            $producto,
            $request->validated()
        );

        return redirect()
            ->route('productos.show', $producto)
            ->with(
                'estado',
                'Producto actualizado correctamente.'
            );
    }

    public function destroy(
        Producto $producto
    ): RedirectResponse {
        $this->authorize('delete', $producto);

        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with(
                'estado',
                'Producto eliminado correctamente.'
            );
    }

    public function restore(
        int $producto
    ): RedirectResponse {
        $producto = Producto::withTrashed()
            ->findOrFail($producto);

        $this->authorize('restore', $producto);

        $producto->restore();

        return redirect()
            ->route('productos.index')
            ->with(
                'estado',
                'Producto restaurado correctamente.'
            );
    }
}
