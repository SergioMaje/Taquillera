<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->bigIncrements('id_municipio');
            $table->string('nombre');
            $table->unsignedBigInteger('id_departamento');
            $table->foreign('id_departamento')->references('id_departamento')->on('departamentos')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
