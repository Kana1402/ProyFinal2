<?php

namespace App\Enums;

enum EstadoActividad: string
{
    
    case PROGRAMADA = 'PROGRAMADA';
    case COMPLETA = 'COMPLETA';
    case CANCELADA = 'CANCELADA';
}
