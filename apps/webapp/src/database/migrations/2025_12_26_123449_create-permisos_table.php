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
        Schema::create('sys_permisos', function (Blueprint $table) {
            $table->bigIncrements('permiso_id');

            $table->string('codigo', 150)->unique();
            $table->string('titulo', 75);
            $table->string('descripcion', 350);
            $table->string('seccion', 350);
            $table->decimal('orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_permisos');
    }
};
