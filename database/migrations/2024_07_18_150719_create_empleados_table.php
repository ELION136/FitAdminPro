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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreign('idUsuario')->references('id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->string('ci', 50)->unique();
            $table->string('nombre', 50);
            $table->string('primerApellido', 50);
            $table->string('segundoApellido', 50)->nullable();
            $table->date('fechaNacimiento');
            $table->enum('genero', ['Masculino', 'Femenino', 'Otro']);
            $table->date('fechaContratacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
