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
        Schema::create('evolucion_clinica_imagenes', function (Blueprint $table) {
            $table->id('id_imagen');
            $table->integer('id_evolucion')->nullable(false);
            $table->string('ruta_imagen');
            $table->timestamps();

            // Clave foránea hacia evolucion_tratamiento
            $table->foreign('id_evolucion')->references('id_evolucion')->on('evolucion_tratamiento')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evolucion_clinica_imagenes');
    }
};
