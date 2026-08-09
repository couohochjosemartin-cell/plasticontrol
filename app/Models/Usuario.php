<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'usuarios';

    protected $fillable = [
        'rol_id',
        'creado_por_id',
        'nombre_completo',
        'usuario',
        'password',
        'estado',
        'ultimo_acceso_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'ultimo_acceso_at' => 'datetime',
        ];
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class);
    }

    public function creadoPor()
{
    return $this->belongsTo(
        Usuario::class,
        'creado_por_id'
    );
}

    public function usuariosCreados(): HasMany
    {
        return $this->hasMany(
            Usuario::class,
            'creado_por_id'
        );
    }

    public function movimientosInventario(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function estaActivo(): bool
    {
        return $this->estado === 'Activo';
    }

    public function esAdministrador(): bool
    {
        return $this->rol?->nombre === 'Administrador';
    }

    public function esCajero(): bool
    {
        return $this->rol?->nombre === 'Cajero';
    }

    public function esInventario(): bool
    {
        return $this->rol?->nombre === 'Inventario';
    }
}