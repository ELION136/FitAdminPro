<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('detalleServicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade')->onUpdate('cascade');
            $table->text('detalle');
            $table->string('urlVideo', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('detalleServicios');
    }
};
