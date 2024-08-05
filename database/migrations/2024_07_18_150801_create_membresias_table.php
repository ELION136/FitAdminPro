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
        Schema::create('membresias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('plan_id')->constrained('planesMembresia')->onDelete('restrict')->onUpdate('cascade');
            $table->date('fechaInicio');
            $table->date('fechaFin');
            $table->enum('estado', ['Activa', 'Expirada', 'Cancelada', 'inactiva']);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('membresias');
    }
};
