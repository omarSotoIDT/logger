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
        Schema::create('rel_usuarios_perfiles', function (Blueprint $table) {
            $table->bigIncrements('rel_usuario_perfil_id');

            $table->unsignedBigInteger('perfil_id');
            $table->unsignedBigInteger('usuario_id');
            $table->string('status', 20)->default('ACTIVO');

            // Auditoría
            $table->timestamp('registro_fecha');
            $table->unsignedBigInteger('registro_autor_id');
            $table->timestamp('actualizacion_fecha')->nullable();
            $table->unsignedBigInteger('actualizacion_autor_id')->nullable();

            $table->foreign('perfil_id')
                ->references('perfil_id')
                ->on('sys_perfiles')
                ->onDelete('cascade');

            $table->foreign('usuario_id')
                ->references('usuario_id')
                ->on('sys_usuarios')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rel_usuarios_perfiles');
    }
};
