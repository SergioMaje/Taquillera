<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_bus', function (Blueprint $table) {
            $table->bigIncrements('id_tipo_bus');
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->boolean('tiene_bano')->default(false);
            $table->boolean('tiene_tv')->default(false);
            $table->boolean('doble_piso')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_bus');
    }
};
