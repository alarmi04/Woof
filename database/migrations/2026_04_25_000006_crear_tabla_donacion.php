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
        Schema::create('Donacion', function (Blueprint $table) {
            $table->id('idDonacion');
            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idMaterial');
            $table->decimal('Cantidad', 10, 2);
            $table->string('Observaciones', 300)->nullable();
            $table->string('Tipo_donacion', 20);
            $table->dateTime('Fecha')->useCurrent();

            $table->foreign('idUsuario')->references('idUsuario')->on('Usuario');
            $table->foreign('idMaterial')->references('idMaterial')->on('Material');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Donacion');
    }
};