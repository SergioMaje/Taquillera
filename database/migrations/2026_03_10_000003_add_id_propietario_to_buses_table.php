<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->unsignedBigInteger('id_propietario')->nullable()->after('id_tipo_bus');
            $table->foreign('id_propietario')
                  ->references('id_propietario')
                  ->on('propietarios')
                  ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropForeign(['id_propietario']);
            $table->dropColumn('id_propietario');
        });
    }
};
