<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->bigIncrements('id_ruta');
            $table->unsignedBigInteger('id_departamento_origen');
            $table->unsignedBigInteger('id_municipio_origen');
            $table->unsignedBigInteger('id_departamento_destino');
            $table->unsignedBigInteger('id_municipio_destino');
            $table->integer('duracion_estimada');
            $table->foreign('id_departamento_origen')->references('id_departamento')->on('departamentos')->restrictOnDelete();
            $table->foreign('id_municipio_origen')->references('id_municipio')->on('municipios')->restrictOnDelete();
            $table->foreign('id_departamento_destino')->references('id_departamento')->on('departamentos')->restrictOnDelete();
            $table->foreign('id_municipio_destino')->references('id_municipio')->on('municipios')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};
