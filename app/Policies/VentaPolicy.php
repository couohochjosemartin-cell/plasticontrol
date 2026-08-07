<?php

namespace App\Policies;

use App\Models\Usuario;
use App\Models\Venta;

class VentaPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->esAdministrador()
            || $usuario->esCajero();
    }

    public function view(
        Usuario $usuario,
        Venta $venta
    ): bool {
        return $usuario->esAdministrador()
            || $usuario->esCajero();
    }

    public function create(Usuario $usuario): bool
    {
        return $usuario->esAdministrador()
            || $usuario->esCajero();
    }
}