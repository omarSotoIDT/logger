<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('logs_detalle', function (Blueprint $table) {
            $table->string('codigo_interno_mensaje', 255)
                ->nullable()
                ->after('codigo_interno');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs_detalle', function (Blueprint $table) {
            $table->dropColumn('codigo_interno_mensaje');
        });
    }
};
