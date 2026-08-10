<?php

namespace App\Enums;

enum TipoMovimiento: string
{
    case ENTRADA = 'Entrada';
    case SALIDA = 'Salida';
}