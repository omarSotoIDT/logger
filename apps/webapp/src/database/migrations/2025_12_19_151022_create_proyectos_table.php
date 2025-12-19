<?php

use App\const\StatusConsts;
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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->bigIncrements('proyecto_id');

            // Datos del formulario
            $table->string('nombre', 120);
            $table->unsignedBigInteger('tipo_proyecto_id');
            $table->string('url_endpoint', 255);
            $table->string('api_key', 120)->unique();

            $table->string('timezone', 60)->default('UTC');

            $table->string('status', 20)->default(StatusConsts::ACTIVO);

            // Auditoría
            $table->timestamp('registro_fecha')->useCurrent();
            $table->unsignedBigInteger('registro_autor_id');
            $table->timestamp('actualizacion_fecha')->nullable();
            $table->unsignedBigInteger('actualizacion_autor_id')->nullable();

            $table->foreign('tipo_proyecto_id')
                ->references('tipo_proyecto_id')
                ->on('cat_tipos_proyecto')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
