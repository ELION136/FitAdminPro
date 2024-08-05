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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('tipo', ['Programa de Entrenamiento', 'Plan de Dieta', 'Video de Entrenamiento']);
            $table->string('nombreServicio', 100);
            $table->text('descripcion')->nullable();
            $table->date('fechaInicio')->nullable();
            $table->date('fechaFin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
