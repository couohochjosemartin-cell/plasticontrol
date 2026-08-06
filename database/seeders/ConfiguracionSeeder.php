<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        Configuracion::updateOrCreate(
            ['id' => 1],
            [
                'nombre_negocio' => config(
                    'plasticontrol.negocio.nombre'
                ),
                'propietario' => config(
                    'plasticontrol.negocio.propietario'
                ),
                'rfc' => null,
                'telefono' => null,
                'direccion' => null,
                'logo' => null,
                'moneda' => 'MXN',
                'iva' => 16.00,
                'zona_horaria' => 'America/Mexico_City',
                'version' => '1.0',
                'desarrollador' => config(
                    'plasticontrol.negocio.desarrollador'
                ),
                'ultima_actualizacion' => now()->toDateString(),
            ]
        );
    }
}