<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Pendiente = 1;
    case Procesando = 2;
    case Enviado = 3;
    case Completado = 4;
    case Fallido = 5;
    case Reembolsado = 6;
    case Cancelado = 7;

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}