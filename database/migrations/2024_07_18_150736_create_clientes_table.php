<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreign('idUsuario')->references('id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nombre', 50);
            $table->string('primerApellido', 50);
            $table->string('segundoApellido', 50)->nullable();
            $table->date('fechaNacimiento');
            $table->enum('genero', ['Masculino', 'Femenino', 'Otro']);
            $table->string('telefono', 15)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('clientes');
    }
};
