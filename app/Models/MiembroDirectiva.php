<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nombre', 'puesto', 'biografia', 'foto_url', 'orden_prioridad'])]
class MiembroDirectiva extends Model
{
    protected $table = 'miembros_directiva';
}
