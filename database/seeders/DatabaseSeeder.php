<?php

namespace Database\Seeders;

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
    }
}
