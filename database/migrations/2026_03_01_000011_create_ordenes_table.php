<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->bigIncrements('id_orden');
            $table->unsignedBigInteger('id_usuario');
            $table->dateTime('fecha_orden');
            $table->string('estado');
            $table->decimal('total', 10, 2);
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
