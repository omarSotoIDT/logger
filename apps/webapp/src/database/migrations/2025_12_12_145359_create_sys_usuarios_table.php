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
        Schema::create('sys_usuarios', function (Blueprint $table) {
            // PK
            $table->bigIncrements('usuario_id');

            // Credenciales
            $table->string('usuario', 20)->unique();
            $table->string('password', 128);

            // General
            $table->string('nombre_corto', 100);
            $table->string('email', 200)->nullable()->unique();
            $table->string('telefono', 25)->nullable();

            // Control de accesos
            $table->timestamp('ultimo_acceso_fecha')->nullable();
            
            // Permisos
            $table->string('status', 10)->default(StatusConsts::ACTIVO);

            // Auditoría
            $table->timestamp('registro_fecha');
            $table->unsignedBigInteger('registro_autor_id');
            $table->timestamp('actualizacion_fecha')->nullable();
            $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_usuarios');
    }
};
