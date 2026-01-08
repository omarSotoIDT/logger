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
        Schema::create('rel_usuarios_proyectos', function (Blueprint $table) {
            $table->bigIncrements('rel_usuario_proyecto_id');

            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('proyecto_id');
            $table->string('status', 20)->default('ACTIVO');

            // Auditoría
            $table->timestamp('registro_fecha');
            $table->unsignedBigInteger('registro_autor_id');
            $table->timestamp('actualizacion_fecha')->nullable();
            $table->unsignedBigInteger('actualizacion_autor_id')->nullable();

            $table->unique(['usuario_id', 'proyecto_id', 'status']);

            $table->foreign('usuario_id')
                ->references('usuario_id')
                ->on('sys_usuarios')
                ->onDelete('cascade');

            $table->foreign('proyecto_id')
                ->references('proyecto_id')
                ->on('proyectos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rel_usuarios_proyectos');
    }
};
