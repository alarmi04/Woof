<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('Usuario', function (Blueprint $table) {
            $table->string('Foto', 255)->nullable()->after('Tiempo_disponible');
        });
    }

    public function down(): void
    {
        Schema::table('Usuario', function (Blueprint $table) {
            $table->dropColumn('Foto');
        });
    }
};
