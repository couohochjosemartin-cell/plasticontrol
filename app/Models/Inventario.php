<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventario extends Model
{
    protected $table = 'inventarios';

    protected $fillable = [
        'producto_id',
        'stock_actual',
        'stock_minimo',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'stock_actual' => 'integer',
            'stock_minimo' => 'integer',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function estaAgotado(): bool
    {
        return $this->stock_actual === 0;
    }

    public function tieneStockBajo(): bool
    {
        return $this->stock_actual > 0
            && $this->stock_actual <= $this->stock_minimo;
    }
}