<?php
/**
 * ==============================================================================
 * FICHERO: database/migrations/2026_04_25_000000_crear_tabla_usuario.php
 * AUTOR: Alberto
 * FECHA CREACIÓN: 2026-04-25
 * FUNCIÓN: Migración de base de datos que define la estructura física
 *          de la tabla 'Usuario' (identidad y perfil de adopción).
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
     * FUNCIÓN: Crea la tabla física 'Usuario' con los campos de datos de contacto
     *          y las variables del cuestionario para el recomendador canino.
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
     * MÉTODO: down
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-04-25
     * FUNCIÓN: Revierte la migración eliminando la tabla física 'Usuario' de MySQL.
     */
    public function down(): void
    {
        Schema::dropIfExists('Usuario');
    }
};