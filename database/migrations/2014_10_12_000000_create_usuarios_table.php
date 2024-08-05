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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('idUsuario');
            $table->string('nombreUsuario', 50)->unique();
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->text('imagen')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // se mantiene por reglas de autenticación
            $table->rememberToken();
            $table->enum('rol', ['Administrador', 'Entrenador', 'Cliente']);
            $table->tinyInteger('estado')->default(1); // 1 para activo, 0 para inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
