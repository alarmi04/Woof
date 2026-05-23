<?php
/**
 * ==============================================================================
 * FICHERO: database/migrations/2026_05_07_205353_create_donaciones_table.php
 * AUTOR: Alberto
 * FECHA CREACIÓN: 2026-05-07
 * FUNCIÓN: Migración de base de datos que define la estructura física y las
 *          restricciones referenciales de la tabla 'Donacion' (registro de aportes).
 * ==============================================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MÉTODO: up
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-05-07
     * FUNCIÓN: Crea la tabla física 'Donacion' con sus relaciones referenciales
     *          onDelete('set null') para usuarios y materiales donados.
     */
    public function up(): void
    {
        Schema::create('Donacion', function (Blueprint $table) {
            $table->id('idDonacion');
            // Relación con el usuario, puede ser NULL para donaciones anónimas
            $table->unsignedBigInteger('idUsuario')->nullable();
            $table->unsignedBigInteger('idMaterial')->nullable();
            $table->decimal('Cantidad', 10, 2);
            $table->string('Observaciones', 300)->nullable();
            $table->string('Tipo_donacion', 20);
            $table->timestamp('Fecha')->useCurrent();

            $table->foreign('idUsuario')->references('idUsuario')->on('Usuario')->onDelete('set null');
            $table->foreign('idMaterial')->references('idMaterial')->on('Material')->onDelete('set null');
        });
    }

    /**
     * MÉTODO: down
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-05-07
     * FUNCIÓN: Revierte la migración eliminando la tabla física 'Donacion' de MySQL.
     */
    public function down(): void
    {
        Schema::dropIfExists('Donacion');
    }
};
