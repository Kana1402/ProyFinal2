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
        // 1. Cambiamos el nombre de la tabla a 'usuarios'
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); // Equivale a tu @Id y @GeneratedValue
            $table->string('username', 50)->unique(); // @Column(nullable = false, unique = true, length = 50)
            $table->string('password'); // @Column(nullable = false)
            
            // Para el Enum, guardamos el valor como un string de longitud máxima 20
            $table->string('role', 20); // @Column(nullable = false, length = 20)
            
            $table->string('correo')->unique(); // @Column(nullable = false, unique = true)
            $table->string('telefono')->nullable(); // Al no tener anotaciones, por defecto acepta null
            
            // Mapeamos tu @Column(updatable = false) con el comportamiento de Laravel
            $table->timestamp('fechaRegistro')->useCurrent(); 
            
            $table->rememberToken(); // Requerido por Laravel si usas autenticación tradicional por sesiones
        });

        // 2. Adaptamos la tabla de reseteo de contraseñas (usa 'correo' en lugar de 'email')
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('correo')->primary(); 
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Adaptamos la tabla de sesiones (si usas autenticación por sesiones web de Laravel)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            // Declaramos la llave foránea apuntando correctamente a 'usuarios'
            $table->foreignId('user_id')->nullable()->index()->constrained('usuarios')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // El orden importa al borrarlas por las llaves foráneas de la tabla 'sessions'
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('usuarios');
    }
};