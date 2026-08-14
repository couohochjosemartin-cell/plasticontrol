<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateConfiguracionRequest;
use App\Models\Configuracion;
use App\Services\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

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

    $directorioRespaldos = storage_path(
        'app/backups'
    );

    $ultimoRespaldo = null;

    if (is_dir($directorioRespaldos)) {
        $archivos = glob(
            $directorioRespaldos
            . DIRECTORY_SEPARATOR
            . '*.sql'
        );

        if ($archivos) {
            usort(
                $archivos,
                fn (
                    string $a,
                    string $b
                ): int => filemtime($b) <=> filemtime($a)
            );

            $ultimoArchivo = $archivos[0];

            $ultimoRespaldo = [
                'nombre' => basename($ultimoArchivo),

                'fecha' => date(
                    'd/m/Y H:i',
                    filemtime($ultimoArchivo)
                ),

                'tamano' => filesize(
                    $ultimoArchivo
                ),
            ];
        }
    }

    return view(
        'configuracion.edit',
        compact(
            'configuracion',
            'ultimoRespaldo'
        )
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

    public function backup(
        BackupService $backupService
    ): RedirectResponse {
        abort_unless(
            auth()->user()?->esAdministrador(),
            403
        );

        try {
            $ruta = $backupService->crearRespaldo();

            return redirect()
                ->route('configuracion.edit')
                ->with(
                    'estado',
                    'Respaldo creado correctamente: '
                    . basename($ruta)
                );
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('configuracion.edit')
                ->withErrors([
                    'backup' =>
                        'No fue posible crear el respaldo del sistema.',
                ]);
        }
    }
}