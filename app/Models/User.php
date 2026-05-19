<?php

namespace App\Models;

use App\Enums\Role; // Importamos tu Enum
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['username', 'password', 'role', 'correo', 'telefono'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'usuarios';

    const CREATED_AT = 'fechaRegistro';
    const UPDATED_AT = null;

    /**
     * Los atributos que deben ser casteados.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'fechaRegistro' => 'datetime',
            'role' => Role::class, 
        ];
    }
}