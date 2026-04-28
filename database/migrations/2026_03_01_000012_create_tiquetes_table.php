<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiquetes', function (Blueprint $table) {
            $table->bigIncrements('id_tiquete');
            $table->unsignedBigInteger('id_orden');
            $table->unsignedBigInteger('id_asiento_viaje');
            $table->dateTime('fecha_compra');
            $table->string('estado');
            $table->decimal('precio_base', 10, 2);
            $table->decimal('precio_final', 10, 2);
            $table->foreign('id_orden')->references('id_orden')->on('ordenes')->cascadeOnDelete();
            $table->foreign('id_asiento_viaje')->references('id_asiento_viaje')->on('asientos_viaje')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiquetes');
    }
};
