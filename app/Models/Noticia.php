<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['titulo', 'contenido', 'imagen_url', 'autor_id', 'fecha_publicacion'])]
class Noticia extends Model
{
    use HasFactory;

    protected $table = 'noticias';

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    protected function casts(): array
    {
        return [
            'fecha_publicacion' => 'datetime',
        ];
    }
}
