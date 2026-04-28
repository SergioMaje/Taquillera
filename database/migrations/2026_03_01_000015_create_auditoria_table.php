<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria', function (Blueprint $table) {
            $table->bigIncrements('id_auditoria');
            $table->string('tabla_afectada');
            $table->integer('id_registro');
            $table->unsignedBigInteger('usuario_id');
            $table->string('accion');
            $table->text('detalle')->nullable();
            $table->dateTime('fecha');
            $table->foreign('usuario_id')->references('id_usuario')->on('usuarios')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria');
    }
};
