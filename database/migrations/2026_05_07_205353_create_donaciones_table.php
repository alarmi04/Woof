<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para crear la tabla de donaciones.
     */
    public function up(): void
    {
        Schema::create('Donacion', function (Blueprint $table) {
            $table->id('idDonacion');
            // Relación con el usuario, puede ser NULL para donaciones anónimas
            $table->unsignedBigInteger('idUsuario')->nullable();
            $table->decimal('Cantidad', 10, 2);
            $table->string('Mensaje', 255)->nullable();
            $table->timestamp('Fecha')->useCurrent();

            $table->foreign('idUsuario')->references('idUsuario')->on('Usuario')->onDelete('set null');
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('Donacion');
    }
};
