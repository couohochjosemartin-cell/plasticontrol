<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdministradorSeeder extends Seeder
{
    public function run(): void
    {
        $password = config('plasticontrol.admin.password');

        if (blank($password)) {
            throw new RuntimeException(
                'Debes definir PLASTICONTROL_ADMIN_PASSWORD en el archivo .env.'
            );
        }

        $rolAdministrador = Rol::where(
            'nombre',
            'Administrador'
        )->firstOrFail();

        Usuario::updateOrCreate(
            [
                'usuario' => config(
                    'plasticontrol.admin.usuario'
                ),
            ],
            [
                'rol_id' => $rolAdministrador->id,
                'creado_por_id' => null,
                'nombre_completo' => config(
                    'plasticontrol.admin.nombre'
                ),
                'password' => Hash::make($password),
                'estado' => 'Activo',
            ]
        );
    }
}