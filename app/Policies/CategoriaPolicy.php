<?php

namespace App\Policies;

use App\Models\Categoria;
use App\Models\Usuario;

class CategoriaPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->esAdministrador()
            || $usuario->esInventario();
    }

    public function view(Usuario $usuario, Categoria $categoria): bool
    {
        return $usuario->esAdministrador()
            || $usuario->esInventario();
    }

    public function create(Usuario $usuario): bool
    {
        return $usuario->esAdministrador();
    }

    public function update(Usuario $usuario, Categoria $categoria): bool
    {
        return $usuario->esAdministrador();
    }

    public function delete(Usuario $usuario, Categoria $categoria): bool
    {
        return $usuario->esAdministrador();
    }

    public function restore(Usuario $usuario, Categoria $categoria): bool
    {
        return $usuario->esAdministrador();
    }

    public function forceDelete(Usuario $usuario, Categoria $categoria): bool
    {
        return false;
    }
}