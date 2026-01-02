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
        Schema::create('sys_perfiles', function(Blueprint $table){
            // PK
            $table->bigIncrements('perfil_id');

            // General
            $table->string('clave',20)->unique();
            $table->string('titulo',45);
            $table->string('descripcion',250)->nullable();

            // Permiso
            $table->string('status',10)->default('ACTIVO');

            // Auditoría
            $table->unsignedBigInteger('registro_autor_id');
            $table->timestamp('registro_fecha');
            $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
            $table->timestamp('actualizacion_fecha')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_perfiles');
    }
};
