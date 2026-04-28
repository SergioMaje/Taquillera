<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tipos_bus', function (Blueprint $table) {
            $table->tinyInteger('columnas_izquierda')->default(2)->after('doble_piso');
            $table->tinyInteger('columnas_derecha')->default(2)->after('columnas_izquierda');
        });
    }

    public function down(): void
    {
        Schema::table('tipos_bus', function (Blueprint $table) {
            $table->dropColumn(['columnas_izquierda', 'columnas_derecha']);
        });
    }
};
