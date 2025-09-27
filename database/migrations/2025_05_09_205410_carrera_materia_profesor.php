<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('carrera_asignatura_profesor')) {
            Schema::create('carrera_asignatura_profesor', function (Blueprint $table) {
                $table->integer('id_carrera');
                $table->integer('id_asignatura');
                $table->integer('id_profesor')->nullable(true);
                $table->integer('carga_horaria');
                $table->tinyInteger('tipo_modulo')->nullable();
                $table->integer('anio');

                $table->primary(['id_carrera', 'id_asignatura']);

                $table->foreign('id_carrera')->references('id')->on('carreras')->onDelete('cascade');
                $table->foreign('id_asignatura')->references('id')->on('asignaturas')->onDelete('cascade');
                $table->foreign('id_profesor')->references('id')->on('profesores')->onDelete('cascade');

                $table->timestamps();
            });
        } else {
            Schema::table('carrera_asignatura_profesor', function (Blueprint $table) {
                $table->tinyInteger('tipo_modulo')->nullable();
            });
        }
        // Migrar los datos existentes de asignaturas.id_carrera a la tabla pivote
        DB::table('asignaturas')
            ->whereNotNull('id_carrera')
            ->get()
            ->each(function ($asignatura) {
                DB::table('carrera_asignatura_profesor')->insert([
                    'id_asignatura' => $asignatura->id,
                    'carga_horaria' => $asignatura->carga_horaria,
                    'tipo_modulo' => $asignatura->tipo_modulo,
                    'anio' => $asignatura->anio,
                    'id_carrera' => $asignatura->id_carrera,
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
