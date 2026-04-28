<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tiquetes', function (Blueprint $table) {
            $table->string('nombre_pasajero')->nullable()->after('id_asiento_viaje');
            $table->string('documento_pasajero')->nullable()->after('nombre_pasajero');
        });
    }

    public function down(): void
    {
        Schema::table('tiquetes', function (Blueprint $table) {
            $table->dropColumn(['nombre_pasajero', 'documento_pasajero']);
        });
    }
};
