<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateConfiguracionRequest;
use App\Models\Configuracion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConfiguracionController extends Controller
{
    public function edit(): View
    {
        abort_unless(
            auth()->user()?->esAdministrador(),
            403
        );

        $configuracion = Configuracion::query()
            ->firstOrFail();

        return view(
            'configuracion.edit',
            compact('configuracion')
        );
    }

    public function update(
        UpdateConfiguracionRequest $request
    ): RedirectResponse {
        $configuracion = Configuracion::query()
            ->firstOrFail();

        $datos = $request->validated();

        $logo = $datos['logo'] ?? null;
        $eliminarLogo = (bool) (
            $datos['eliminar_logo'] ?? false
        );

        unset(
            $datos['logo'],
            $datos['eliminar_logo']
        );

        if (
            $eliminarLogo
            && $configuracion->logo
        ) {
            Storage::disk('public')->delete(
                $configuracion->logo
            );

            $datos['logo'] = null;
        }

        if ($logo) {
            if ($configuracion->logo) {
                Storage::disk('public')->delete(
                    $configuracion->logo
                );
            }

            $datos['logo'] = $logo->store(
                'configuracion',
                'public'
            );
        }

        $datos['ultima_actualizacion'] = today();

        $configuracion->update($datos);

        return redirect()
            ->route('configuracion.edit')
            ->with(
                'estado',
                'Configuración actualizada correctamente.'
            );
    }
}