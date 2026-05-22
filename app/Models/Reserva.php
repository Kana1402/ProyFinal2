<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'usuario_id',
        'actividad_id',
        'cantidad_personas',
        'estado',
        'fecha_reserva',
        'notas'
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con ActividadProgramada
    public function actividad()
    {
        return $this->belongsTo(ActividadProgramada::class, 'actividad_id');
    }

    // Auto asignar fecha al crear
    protected static function booted()
    {
        static::creating(function ($reserva) {
            $reserva->fecha_reserva = now();
        });
    }
}