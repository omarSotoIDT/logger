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
        Schema::create('rel_usuarios_permisos', function (Blueprint $table) {
            $table->bigIncrements('rel_usuario_permiso_id');

            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('permiso_id');

            $table->string('status', 255)->default('ACTIVO');

            $table->unsignedBigInteger('registro_autor_id');
            $table->timestamp('registro_fecha');

            $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
            $table->timestamp('actualizacion_fecha')->nullable();

            // FKs
            $table->foreign('usuario_id')
                ->references('usuario_id')
                ->on('sys_usuarios');

            $table->foreign('permiso_id')
                ->references('permiso_id')
                ->on('sys_permisos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rel_usuarios');
    }
};
