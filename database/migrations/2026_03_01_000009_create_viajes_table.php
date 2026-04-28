<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viajes', function (Blueprint $table) {
            $table->bigIncrements('id_viaje');
            $table->unsignedBigInteger('id_bus');
            $table->unsignedBigInteger('id_ruta');
            $table->date('fecha_salida');
            $table->time('hora_salida');
            $table->time('hora_llegada_real')->nullable();
            $table->decimal('precio_base', 10, 2);
            $table->decimal('precio_final', 10, 2);
            $table->string('estado');
            $table->string('estado_real')->nullable();
            $table->integer('asientos_libres');
            $table->foreign('id_bus')->references('id_bus')->on('buses')->restrictOnDelete();
            $table->foreign('id_ruta')->references('id_ruta')->on('rutas')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
