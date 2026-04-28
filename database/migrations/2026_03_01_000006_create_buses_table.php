<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->bigIncrements('id_bus');
            $table->string('placa')->unique();
            $table->unsignedBigInteger('id_tipo_bus');
            $table->integer('capacidad');
            $table->foreign('id_tipo_bus')->references('id_tipo_bus')->on('tipos_bus')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
