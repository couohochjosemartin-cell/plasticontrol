<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    public function estaActiva(): bool
    {
        return $this->estado === 'Activa';
    }
}
