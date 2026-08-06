<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso completo al sistema.',
            ],
            [
                'nombre' => 'Cajero',
                'descripcion' => 'Acceso al Dashboard y Punto de Venta.',
            ],
            [
                'nombre' => 'Inventario',
                'descripcion' => 'Consulta de catálogos y control de inventario.',
            ],
        ];

        foreach ($roles as $rol) {
            Rol::updateOrCreate(
                ['nombre' => $rol['nombre']],
                $rol
            );
        }
    }
}
