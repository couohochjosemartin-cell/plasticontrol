<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\EstadoVenta;
use App\Enums\MetodoPago;

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
            'estado' => EstadoVenta::class,
            'metodo_pago' => MetodoPago::class,
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
    return $this->estado === EstadoVenta::COMPLETADA;
}
}