<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cursadas', function (Blueprint $table) {
            $table->integer('id_carrera')->nullable(true);
            $table->foreign('id_carrera')->references('id')->on('carreras');
        });
        DB::statement('
            UPDATE cursadas
            INNER JOIN asignaturas ON cursadas.id_asignatura = asignaturas.id
            SET cursadas.id_carrera = asignaturas.id_carrera
            WHERE asignaturas.id_carrera IS NOT NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
