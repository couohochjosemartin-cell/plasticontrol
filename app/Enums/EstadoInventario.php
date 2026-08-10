<?php

namespace App\Enums;

enum EstadoInventario: string
{
    case DISPONIBLE = 'Disponible';
    case STOCK_BAJO = 'Stock bajo';
    case AGOTADO = 'Agotado';
}