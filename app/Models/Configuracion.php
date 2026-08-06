<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = [
        'nombre_negocio',
        'propietario',
        'rfc',
        'telefono',
        'direccion',
        'logo',
        'moneda',
        'iva',
        'zona_horaria',
        'version',
        'desarrollador',
        'ultima_actualizacion',
    ];

    protected function casts(): array
    {
        return [
            'iva' => 'decimal:2',
            'ultima_actualizacion' => 'date',
        ];
    }
}