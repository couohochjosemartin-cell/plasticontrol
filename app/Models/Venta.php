<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'folio',
        'usuario_id',
        'metodo_pago',
        'subtotal',
        'descuento',
        'total',
        'pago_recibido',
        'cambio',
        'estado',
        'fecha_venta',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'descuento' => 'decimal:2',
            'total' => 'decimal:2',
            'pago_recibido' => 'decimal:2',
            'cambio' => 'decimal:2',
            'fecha_venta' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function estaCompletada(): bool
    {
        return $this->estado === 'Completada';
    }
}