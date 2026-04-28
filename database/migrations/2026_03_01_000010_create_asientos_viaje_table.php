<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asientos_viaje', function (Blueprint $table) {
            $table->bigIncrements('id_asiento_viaje');
            $table->unsignedBigInteger('id_viaje');
            $table->unsignedBigInteger('id_asiento');
            $table->string('estado');
            $table->foreign('id_viaje')->references('id_viaje')->on('viajes')->cascadeOnDelete();
            $table->foreign('id_asiento')->references('id_asiento')->on('asientos')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asientos_viaje');
    }
};
