<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asientos', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_asiento']);
            $table->unsignedBigInteger('id_tipo_asiento')->nullable()->change();
            $table->foreign('id_tipo_asiento')->references('id_tipo_asiento')->on('tipos_asiento')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('asientos', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_asiento']);
            $table->unsignedBigInteger('id_tipo_asiento')->nullable(false)->change();
            $table->foreign('id_tipo_asiento')->references('id_tipo_asiento')->on('tipos_asiento')->restrictOnDelete();
        });
    }
};
