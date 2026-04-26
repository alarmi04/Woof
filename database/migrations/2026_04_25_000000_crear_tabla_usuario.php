<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Usuario', function (Blueprint $table) {
            $table->id('idUsuario');
            $table->string('Nombre', 100);
            $table->string('Correo', 150)->unique();
            $table->string('Contrasena', 255);
            $table->string('Telefono', 20)->nullable();
            $table->string('Direccion', 200)->nullable();
            $table->string('Tipo_vivienda', 50)->nullable();
            $table->tinyInteger('Numero_hijos')->default(0);
            $table->tinyInteger('Nivel_actividad')->nullable();
            $table->tinyInteger('Experiencia_mascotas')->nullable();
            $table->tinyInteger('Tiempo_disponible')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Usuario');
    }
};