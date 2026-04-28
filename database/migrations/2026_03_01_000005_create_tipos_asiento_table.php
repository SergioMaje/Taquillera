<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_asiento', function (Blueprint $table) {
            $table->bigIncrements('id_tipo_asiento');
            $table->string('codigo');
            $table->string('color')->nullable();
            $table->string('icono')->nullable();
            $table->string('descripcion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_asiento');
    }
};
