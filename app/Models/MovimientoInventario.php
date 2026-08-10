<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\TipoMovimiento;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'inventario_id',
        'usuario_id',
        'tipo_movimiento',
        'cantidad',
        'stock_anterior',
        'stock_resultante',
        'motivo',
        'referencia_tipo',
        'referencia_id',
        'fecha_movimiento',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'stock_anterior' => 'integer',
            'stock_resultante' => 'integer',
            'fecha_movimiento' => 'datetime',
            'tipo_movimiento' => TipoMovimiento::class,
        ];
    }

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function esEntrada(): bool
{
    return $this->tipo_movimiento === TipoMovimiento::ENTRADA;
}

public function esSalida(): bool
{
    return $this->tipo_movimiento === TipoMovimiento::SALIDA;
}
}