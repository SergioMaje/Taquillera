<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asientos', function (Blueprint $table) {
            $table->bigIncrements('id_asiento');
            $table->unsignedBigInteger('id_bus');
            $table->integer('numero');
            $table->integer('pos_x');
            $table->integer('pos_y');
            $table->integer('piso');
            $table->unsignedBigInteger('id_tipo_asiento');
            $table->foreign('id_bus')->references('id_bus')->on('buses')->cascadeOnDelete();
            $table->foreign('id_tipo_asiento')->references('id_tipo_asiento')->on('tipos_asiento')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asientos');
    }
};
