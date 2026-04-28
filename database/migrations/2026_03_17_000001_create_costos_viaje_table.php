<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('costos_viaje', function (Blueprint $table) {
            $table->bigIncrements('id_costo_viaje');
            $table->unsignedBigInteger('id_viaje');
            $table->enum('concepto', ['combustible', 'peajes', 'conductor', 'mantenimiento', 'otros']);
            $table->string('descripcion')->nullable();
            $table->decimal('monto', 10, 2);
            $table->foreign('id_viaje')->references('id_viaje')->on('viajes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('costos_viaje');
    }
};
