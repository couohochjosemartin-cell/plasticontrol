<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Categoria::class);

        $buscar = trim((string) $request->input('buscar'));
        $estado = $request->input('estado', 'todas');

        $categorias = Categoria::query()
            ->withCount('productos')
            ->when(
                $estado === 'eliminadas',
                fn ($query) => $query->onlyTrashed()
            )
            ->when(
                $estado === 'todas',
                fn ($query) => $query->withTrashed()
            )
            ->when(
                in_array($estado, ['Activa', 'Inactiva'], true),
                fn ($query) => $query->where('estado', $estado)
            )
            ->when(
                $buscar !== '',
                function ($query) use ($buscar): void {
                    $query->where(function ($subquery) use ($buscar): void {
                        $subquery
                            ->where('nombre', 'like', "%{$buscar}%")
                            ->orWhere('descripcion', 'like', "%{$buscar}%");
                    });
                }
            )
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $resumen = [
            'total' => Categoria::withTrashed()->count(),
            'activas' => Categoria::where('estado', 'Activa')->count(),
            'inactivas' => Categoria::where('estado', 'Inactiva')->count(),
            'productos' => Categoria::withTrashed()
                ->withCount('productos')
                ->get()
                ->sum('productos_count'),
        ];

        return view(
            'categorias.index',
            compact(
                'categorias',
                'buscar',
                'estado',
                'resumen'
            )
        );
    }

    public function create(): View
    {
        $this->authorize('create', Categoria::class);

        return view('categorias.create');
    }

    public function store(
        StoreCategoriaRequest $request
    ): RedirectResponse {
        Categoria::create($request->validated());

        return redirect()
            ->route('categorias.index')
            ->with(
                'estado',
                'Categoría registrada correctamente.'
            );
    }

    public function show(Categoria $categoria): View
    {
        $this->authorize('view', $categoria);

        $categoria->loadCount('productos');

        return view(
            'categorias.show',
            compact('categoria')
        );
    }

    public function edit(Categoria $categoria): View
    {
        $this->authorize('update', $categoria);

        return view(
            'categorias.edit',
            compact('categoria')
        );
    }

    public function update(
        UpdateCategoriaRequest $request,
        Categoria $categoria
    ): RedirectResponse {
        $categoria->update($request->validated());

        return redirect()
            ->route('categorias.index')
            ->with(
                'estado',
                'Categoría actualizada correctamente.'
            );
    }

    public function destroy(
        Categoria $categoria
    ): RedirectResponse {
        $this->authorize('delete', $categoria);

        $categoria->delete();

        return redirect()
            ->route('categorias.index')
            ->with(
                'estado',
                'Categoría eliminada correctamente.'
            );
    }

    public function restore(
        int $categoria
    ): RedirectResponse {
        $categoria = Categoria::withTrashed()
            ->findOrFail($categoria);

        $this->authorize('restore', $categoria);

        $categoria->restore();

        return redirect()
            ->route('categorias.index')
            ->with(
                'estado',
                'Categoría restaurada correctamente.'
            );
    }
}