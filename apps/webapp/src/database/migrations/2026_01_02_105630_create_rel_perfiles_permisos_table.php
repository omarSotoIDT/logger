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
        Schema::create('rel_perfiles_permisos', function (Blueprint $table) {
            $table->bigIncrements('rel_permiso_perfil_id');

            $table->unsignedBigInteger('perfil_id');
            $table->unsignedBigInteger('permiso_id');

            // Auditoría
            $table->timestamp('registro_fecha');
            $table->unsignedBigInteger('registro_autor_id');

            $table->foreign('perfil_id')
                ->references('perfil_id')
                ->on('sys_perfiles')
                ->onDelete('cascade');

            $table->foreign('permiso_id')
                ->references('permiso_id')
                ->on('sys_permisos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rel_perfiles_permisos');
    }
};
