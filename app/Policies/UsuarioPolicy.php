<?php

namespace App\Policies;

use App\Models\Usuario;

class UsuarioPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->esAdministrador();
    }

    public function view(
        Usuario $usuario,
        Usuario $usuarioAdministrado
    ): bool {
        return $usuario->esAdministrador();
    }

    public function create(Usuario $usuario): bool
    {
        return $usuario->esAdministrador();
    }

    public function update(
        Usuario $usuario,
        Usuario $usuarioAdministrado
    ): bool {
        return $usuario->esAdministrador();
    }

    public function delete(
        Usuario $usuario,
        Usuario $usuarioAdministrado
    ): bool {
        return $usuario->esAdministrador()
            && $usuario->id !== $usuarioAdministrado->id;
    }

    public function restore(
        Usuario $usuario,
        Usuario $usuarioAdministrado
    ): bool {
        return $usuario->esAdministrador();
    }

    public function forceDelete(
        Usuario $usuario,
        Usuario $usuarioAdministrado
    ): bool {
        return false;
    }
}