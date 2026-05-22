<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */

    public function run()
    {
        User::create([
            'username' => 'usuarioadmin',
            'correo' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => Role::ADMIN,
            'telefono' => '12345678'
        ]);

        User::create([
            'username' => 'usuario',
            'correo' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'role' => Role::USER,
            'telefono' => '12345678'
        ]);

        // ───────────────── NOTICIAS ─────────────────

        DB::table('noticias')->insert([
            [
                'titulo' => 'Nueva jornada de pesca',
                'contenido' => 'Se realizó una jornada de pesca sostenible en Cahuita.',
                'imagen_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e',
                'autor_id' => 1,
                'fecha_publicacion' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Capacitación ambiental',
                'contenido' => 'Los pescadores participaron en una capacitación ambiental.',
                'imagen_url' => 'https://images.unsplash.com/photo-1493558103817-58b2924bce98',
                'autor_id' => 1,
                'fecha_publicacion' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ───────────────── SERVICIOS ─────────────────

        DB::table('servicios')->insert([
            [
                'titulo' => 'Tour de pesca',
                'descripcion' => 'Tour guiado de pesca artesanal.',
                'precio' => 25000,
                'imagen_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titulo' => 'Tour en bote',
                'descripcion' => 'Recorrido por Cahuita en bote.',
                'precio' => 18000,
                'imagen_url' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
// ───────────────── ACTIVIDADES PROGRAMADAS ─────────────────

DB::table('actividades_programadas')->insert([
    [
        'servicio_id' => 1,
        'fecha_hora' => now()->addDays(2),
        'cupo_maximo' => 20,
        'cupo_disponible' => 20,
        'estado' => 'DISPONIBLE',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'servicio_id' => 1,
        'fecha_hora' => now()->addDays(5),
        'cupo_maximo' => 15,
        'cupo_disponible' => 15,
        'estado' => 'DISPONIBLE',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'servicio_id' => 2,
        'fecha_hora' => now()->addDays(3),
        'cupo_maximo' => 10,
        'cupo_disponible' => 10,
        'estado' => 'DISPONIBLE',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'servicio_id' => 2,
        'fecha_hora' => now()->addWeek(),
        'cupo_maximo' => 12,
        'cupo_disponible' => 12,
        'estado' => 'DISPONIBLE',
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
    }
}
