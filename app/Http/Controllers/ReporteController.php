<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReporteRequest;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Configuracion;

class ReporteController extends Controller
{
    public function index(
        ReporteRequest $request,
        ReportService $reportService
    ): View {
        $fechaDesde = $request->input(
            'fecha_desde',
            now()
                ->startOfMonth()
                ->toDateString()
        );

        $fechaHasta = $request->input(
            'fecha_hasta',
            now()->toDateString()
        );

        return view(
            'reportes.index',
            $reportService->obtenerReporte(
                $fechaDesde,
                $fechaHasta
            )
        );
    }

    public function pdf(
    ReporteRequest $request,
    ReportService $reportService
): Response {
    $fechaDesde = $request->input(
        'fecha_desde',
        now()
            ->startOfMonth()
            ->toDateString()
    );

    $fechaHasta = $request->input(
        'fecha_hasta',
        now()->toDateString()
    );

    /*
    |--------------------------------------------------------------------------
    | El PDF obtiene TODAS las operaciones
    |--------------------------------------------------------------------------
    */

    $datos = $reportService->obtenerReporte(
        $fechaDesde,
        $fechaHasta,
        false
    );

    /*
    |--------------------------------------------------------------------------
    | Información del negocio
    |--------------------------------------------------------------------------
    */

    $configuracion = Configuracion::query()
        ->first();

    /*
    |--------------------------------------------------------------------------
    | Logo convertido a Base64
    |--------------------------------------------------------------------------
    */

    $logoBase64 = null;

    if ($configuracion?->logo) {
        $rutaLogo = public_path(
            'storage/' . $configuracion->logo
        );

        if (is_file($rutaLogo)) {
            $extension = strtolower(
                pathinfo(
                    $rutaLogo,
                    PATHINFO_EXTENSION
                )
            );

            $mime = match ($extension) {
                'png' => 'image/png',
                'webp' => 'image/webp',
                default => 'image/jpeg',
            };

            $logoBase64 =
                'data:'
                . $mime
                . ';base64,'
                . base64_encode(
                    file_get_contents($rutaLogo)
                );
        }
    }

    $datos['configuracion'] = $configuracion;
    $datos['logoBase64'] = $logoBase64;

    $pdf = Pdf::loadView(
        'reportes.pdf',
        $datos
    )->setPaper(
        'letter',
        'landscape'
    );

    $nombre = sprintf(
        'reporte-plasticontrol-%s-a-%s.pdf',
        $fechaDesde,
        $fechaHasta
    );

    return $pdf->download($nombre);
}
}