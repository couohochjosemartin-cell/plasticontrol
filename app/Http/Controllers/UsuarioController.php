<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Usuario::class);

        $buscar = trim((string) $request->input('buscar'));
        $rolId = $request->input('rol');
        $estado = $request->input('estado', 'todos');

        $usuarios = Usuario::query()
            ->with('rol')
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
                filled($rolId),
                fn ($query) => $query->where('rol_id', $rolId)
            )
            ->when(
                $buscar !== '',
                function ($query) use ($buscar): void {
                    $query->where(
                        function ($subquery) use ($buscar): void {
                            $subquery
                                ->where(
                                    'nombre_completo',
                                    'like',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'usuario',
                                    'like',
                                    "%{$buscar}%"
                                );
                        }
                    );
                }
            )
            ->orderBy('nombre_completo')
            ->paginate(10)
            ->withQueryString();

        $roles = Rol::query()
            ->orderBy('nombre')
            ->get();

        $resumen = [
            'total' => Usuario::withTrashed()->count(),

            'activos' => Usuario::query()
                ->where('estado', 'Activo')
                ->count(),

            'inactivos' => Usuario::query()
                ->where('estado', 'Inactivo')
                ->count(),

            'eliminados' => Usuario::onlyTrashed()->count(),
        ];

        return view(
            'usuarios.index',
            compact(
                'usuarios',
                'roles',
                'buscar',
                'rolId',
                'estado',
                'resumen'
            )
        );
    }

    public function create(): View
    {
        $this->authorize('create', Usuario::class);

        $roles = Rol::query()
            ->orderBy('nombre')
            ->get();

        return view(
            'usuarios.create',
            compact('roles')
        );
    }

    public function store(
        StoreUsuarioRequest $request
    ): RedirectResponse {
        $datos = $request->validated();

        $datos['password'] = Hash::make(
            $datos['password']
        );

        $datos['creado_por_id'] = $request->user()->id;

        Usuario::create($datos);

        return redirect()
            ->route('usuarios.index')
            ->with(
                'estado',
                'Usuario registrado correctamente.'
            );
    }

    public function show(Usuario $usuario): View
    {
        $this->authorize('view', $usuario);

        $usuario->load([
            'rol',
            'creadoPor',
        ]);

        return view(
            'usuarios.show',
            compact('usuario')
        );
    }

    public function edit(Usuario $usuario): View
    {
        $this->authorize('update', $usuario);

        $roles = Rol::query()
            ->orderBy('nombre')
            ->get();

        return view(
            'usuarios.edit',
            compact(
                'usuario',
                'roles'
            )
        );
    }

    public function update(
        UpdateUsuarioRequest $request,
        Usuario $usuario
    ): RedirectResponse {
        $datos = $request->validated();

        if (empty($datos['password'])) {
            unset($datos['password']);
        } else {
            $datos['password'] = Hash::make(
                $datos['password']
            );
        }

        if (
            $request->user()->id === $usuario->id
            && $datos['estado'] !== 'Activo'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'estado' => 'No puedes desactivar tu propia cuenta.',
                ]);
        }

        $usuario->update($datos);

        return redirect()
            ->route('usuarios.index')
            ->with(
                'estado',
                'Usuario actualizado correctamente.'
            );
    }

    public function destroy(
        Usuario $usuario
    ): RedirectResponse {
        $this->authorize('delete', $usuario);

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with(
                'estado',
                'Usuario eliminado correctamente.'
            );
    }

    public function restore(
        int $usuario
    ): RedirectResponse {
        $usuario = Usuario::withTrashed()
            ->findOrFail($usuario);

        $this->authorize('restore', $usuario);

        $usuario->restore();

        return redirect()
            ->route('usuarios.index')
            ->with(
                'estado',
                'Usuario restaurado correctamente.'
            );
    }
}