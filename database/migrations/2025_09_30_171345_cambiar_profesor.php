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
        Schema::table('carrera_asignatura_profesor', function (Blueprint $table) {
         $table->integer('id_profesor')->nullable(false)->change();
        });
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
