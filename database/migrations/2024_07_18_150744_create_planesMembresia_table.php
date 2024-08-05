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
        Schema::create('planesMembresia', function (Blueprint $table) {
            $table->id();
            $table->string('nombrePlan', 50);
            $table->text('descripcion')->nullable();
            $table->integer('duracion');
            $table->decimal('precio', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('planesMembresia');
    }
};
