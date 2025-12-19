<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('cat_tipos_proyecto', function (Blueprint $table) {
            $table->bigIncrements('tipo_proyecto_id');

            $table->string('nombre', 80)->unique();
            $table->string('status', 20)->default('ACTIVO');

            // Auditoría
            $table->timestamp('registro_fecha')->useCurrent();
            $table->unsignedBigInteger('registro_autor_id');
            $table->timestamp('actualizacion_fecha')->nullable();
            $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_tipos_proyecto');
    }
};
