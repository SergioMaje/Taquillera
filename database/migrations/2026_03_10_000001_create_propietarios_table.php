<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propietarios', function (Blueprint $table) {
            $table->bigIncrements('id_propietario');
            $table->enum('tipo', ['empresa', 'socio']);
            $table->string('nombre');
            $table->string('cedula_nit')->unique();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propietarios');
    }
};
