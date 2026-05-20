<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['titulo', 'descripcion', 'precio', 'imagen_url'])]
class Servicio extends Model
{
    protected $table = 'servicios';

    public function actividades(): HasMany
    {
        return $this->hasMany(ActividadProgramada::class, 'servicio_id');
    }

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
        ];
    }
}
