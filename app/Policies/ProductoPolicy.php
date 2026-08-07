<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\Usuario;

class ProductoPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->esAdministrador()
            || $usuario->esInventario();
    }

    public function view(
        Usuario $usuario,
        Producto $producto
    ): bool {
        return $usuario->esAdministrador()
            || $usuario->esInventario();
    }

    public function create(Usuario $usuario): bool
    {
        return $usuario->esAdministrador();
    }

    public function update(
        Usuario $usuario,
        Producto $producto
    ): bool {
        return $usuario->esAdministrador();
    }

    public function delete(
        Usuario $usuario,
        Producto $producto
    ): bool {
        return $usuario->esAdministrador();
    }

    public function restore(
        Usuario $usuario,
        Producto $producto
    ): bool {
        return $usuario->esAdministrador();
    }

    public function forceDelete(
        Usuario $usuario,
        Producto $producto
    ): bool {
        return false;
    }
}
