<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('correlatividad', function (Blueprint $table) {
            $table->addColumn('integer', 'id_asignatura_correlativa');
            $table->addColumn('integer', 'id_asignatura');
            $table->addColumn('integer', 'id_carrera');
            $table->primary(['id_asignatura', 'id_asignatura_correlativa', 'id_carrera']);

            // Foreign keys
            $table->foreign('id_asignatura')->references('id')->on('asignaturas');
            $table->foreign('id_asignatura_correlativa')->references('id')->on('asignaturas');
            $table->foreign('id_carrera')->references('id')->on('carreras');

        });

        DB::statement('
            INSERT INTO correlatividad (id_asignatura, id_asignatura_correlativa, id_carrera)
            SELECT
                c.id_asignatura,
                c.asignatura_correlativa,
                a1.id_carrera
            FROM correlatividades c
            JOIN asignaturas a1 ON c.id_asignatura = a1.id;
        ');

        Schema::dropIfExists('correlatividades');
        Schema::rename('correlatividad', 'correlatividades');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
