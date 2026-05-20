<?php

namespace App\Models;

use App\Enums\EstadoActividad;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['servicio_id', 'fecha_hora', 'cupo_maximo', 'cupo_disponible', 'estado'])]
class ActividadProgramada extends Model
{
    protected $table = 'actividades_programadas';

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'actividad_id');
    }

    protected function casts(): array
    {
        return [
            'fecha_hora' => 'datetime',
            'estado' => EstadoActividad::class,
        ];
    }
}
