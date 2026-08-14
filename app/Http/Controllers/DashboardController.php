<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(
        DashboardService $dashboardService
    ): View {
        return view(
            'dashboard',
            $dashboardService->obtenerDatos()
        );
    }
}