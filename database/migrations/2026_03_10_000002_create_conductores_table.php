<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conductores', function (Blueprint $table) {
            $table->bigIncrements('id_conductor');
            $table->string('nombre');
            $table->string('cedula')->unique();
            $table->string('licencia')->unique();
            $table->string('telefono')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conductores');
    }
};
