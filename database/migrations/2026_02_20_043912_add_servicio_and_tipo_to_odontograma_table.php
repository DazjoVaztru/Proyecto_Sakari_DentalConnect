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
        Schema::table('odontograma', function (Blueprint $table) {
            // 1. Usamos integer() porque id_servicio en catalogo_servicios es INT
            // 2. Lo ponemos after('id_cita') que es el nombre real en tu BD
            $table->integer('id_servicio')->nullable()->after('id_cita');

            // Añadimos el tipo de registro para diferenciar colores (Azul = hallazgo, Rojo = tratamiento)
            $table->enum('tipo_registro', ['hallazgo', 'tratamiento'])->default('tratamiento')->after('id_servicio');

            // Relación con el catálogo de servicios (tu tabla se llama catalogo_servicios)
            $table->foreign('id_servicio')->references('id_servicio')->on('catalogo_servicios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('odontograma', function (Blueprint $table) {
            $table->dropForeign(['id_servicio']);
            $table->dropColumn(['id_servicio', 'tipo_registro']);
        });
    }
};