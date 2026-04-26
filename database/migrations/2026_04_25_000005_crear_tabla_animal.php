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
        Schema::create('Animal', function (Blueprint $table) {
            $table->id('idAnimal');
            $table->string('Nombre', 100);
            $table->string('Raza', 100)->nullable();
            $table->string('Genero', 10)->nullable();
            $table->string('Edad', 10)->nullable();
            $table->string('Tamano', 20)->nullable();
            $table->string('Estado', 30)->nullable();
            $table->string('Descripcion', 500)->nullable();
            $table->tinyInteger('Nivel_actividad')->nullable();
            $table->string('Vivienda_recomendada', 50)->nullable();
            $table->boolean('Apto_ninos')->nullable();
            $table->boolean('Experiencia_requerida')->nullable();
            $table->tinyInteger('Tiempo_requerido')->nullable();
            $table->string('Imagen', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Animal');
    }
};
