<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('miembros_directiva', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('puesto', 100);
            $table->text('biografia')->nullable();
            $table->string('foto_url')->nullable();
            $table->integer('orden_prioridad')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('miembros_directiva');
    }
};
