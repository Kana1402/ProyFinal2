<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use App\Enums\Role;
use App\Models\Noticia;
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

        // ───────────────── ACTIVIDAD PROGRAMADA ─────────────────

        DB::table('actividades_programadas')->insert([
            [
                'servicio_id' => 1,
                'fecha_hora' => now()->addDays(7),
                'cupo_maximo' => 10,
                'cupo_disponible' => 10,
                'estado' => 'PROGRAMADA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ───────────────── JUNTA DIRECTIVA ─────────────────

       // #[Fillable(['nombre', 'puesto', 'biografia', 'foto_url', 'orden_prioridad'])]
        DB::table('miembros_directiva')->insert([
            [
                'nombre' => 'Juan Pérez',
                'puesto' => 'Presidente',
                'biografia' => 'Juan es un pescador con 20 años de experiencia en Cahuita.',
                'foto_url' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91',
                'orden_prioridad' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'María Gómez',
                'puesto' => 'Vicepresidenta',
                'biografia' => 'María es una líder comunitaria comprometida con el desarrollo sostenible.',
                'foto_url' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91',
                'orden_prioridad' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
