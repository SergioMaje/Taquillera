<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_conductor')->nullable()->after('id_bus');
            $table->foreign('id_conductor')
                  ->references('id_conductor')
                  ->on('conductores')
                  ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropForeign(['id_conductor']);
            $table->dropColumn('id_conductor');
        });
    }
};
