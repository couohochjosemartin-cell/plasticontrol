<?php

namespace App\Policies;

use App\Models\Inventario;
use App\Models\Usuario;

class InventarioPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->esAdministrador()
            || $usuario->esInventario();
    }

    public function view(
        Usuario $usuario,
        Inventario $inventario
    ): bool {
        return $usuario->esAdministrador()
            || $usuario->esInventario();
    }

    public function update(
        Usuario $usuario,
        Inventario $inventario
    ): bool {
        return $usuario->esAdministrador()
            || $usuario->esInventario();
    }
}