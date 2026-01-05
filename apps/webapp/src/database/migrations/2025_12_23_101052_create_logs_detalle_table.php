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
        Schema::create('logs_detalle', function (Blueprint $table) {
            $table->bigIncrements('log_detalle_id');
            $table->unsignedBigInteger('log_id');

            $table->string('codigo_excepcion');
            $table->string('codigo_interno', 50);
            $table->text('mensaje');
            $table->string('nivel', 20);
            $table->timestamp('fecha_hora_log');
            
            // Ubicación
            $table->string('archivo', 700)->nullable();
            $table->unsignedInteger('linea')->nullable();

            // Diagnóstico
            $table->longText('stacktrace')->nullable();
            
            // Auditoría
            $table->timestamp('registro_fecha');
            $table->unsignedBigInteger('registro_autor_id');
            $table->timestamp('actualizacion_fecha')->nullable();
            $table->unsignedBigInteger('actualizacion_autor_id')->nullable();

            $table->foreign('log_id')
                ->references('log_id')
                ->on('logs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_detalle');
    }
};
