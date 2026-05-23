<?php
/**
 * ==============================================================================
 * FICHERO: database/migrations/2026_04_25_000005_crear_tabla_animal.php
 * AUTOR: Alberto
 * FECHA CREACIÓN: 2026-04-25
 * FUNCIÓN: Migración de base de datos que define la estructura física y las
 *          restricciones referenciales de la tabla 'Animal' (catálogo canino).
 * ==============================================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * MÉTODO: up
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-04-25
     * FUNCIÓN: Crea la tabla física 'Animal' con sus campos de caracterización canina
     *          y la clave foránea vinculante hacia la tabla 'Usuario'.
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
            $table->unsignedBigInteger('idUsuario')->nullable();

            $table->foreign('idUsuario')->references('idUsuario')->on('Usuario');
        });
    }

    /**
     * MÉTODO: down
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-04-25
     * FUNCIÓN: Revierte la migración eliminando la tabla física 'Animal' de MySQL.
     */
    public function down(): void
    {
        Schema::dropIfExists('Animal');
    }
};
