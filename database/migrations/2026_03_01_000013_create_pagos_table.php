<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->bigIncrements('id_pago');
            $table->unsignedBigInteger('id_orden');
            $table->string('metodo_pago');
            $table->decimal('monto', 10, 2);
            $table->string('estado');
            $table->dateTime('fecha_pago');
            $table->foreign('id_orden')->references('id_orden')->on('ordenes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
