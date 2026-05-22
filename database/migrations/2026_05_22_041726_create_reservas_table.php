<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {

            $table->id();

            // Usuario
            $table->foreignId('usuario_id')
                  ->constrained('usuarios')
                  ->onDelete('cascade');

            // Actividad
            $table->foreignId('actividad_id')
                  ->constrained('actividades_programadas')
                  ->onDelete('cascade');

            // Cantidad de personas
            $table->integer('cantidad_personas');

            // Estado
            $table->enum('estado', [
                'PENDIENTE',
                'CONFIRMADA',
                'CANCELADA'
            ])->default('PENDIENTE');

            // Fecha reserva
            $table->dateTime('fecha_reserva');

            // Notas
            $table->text('notas')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};