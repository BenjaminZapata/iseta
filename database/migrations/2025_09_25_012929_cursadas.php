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
        Schema::table('cursadas', function (Blueprint $table) {
            $table->integer('id_carrera')->nullable(true);
            $table->foreign('id_carrera')->references('id')->on('carreras');
        });
        DB::statement('
            UPDATE cursadas
            SET 
                aprobada = CASE 
                    WHEN condicion = 2 THEN 4
                    WHEN condicion = 3 THEN 5
                END,
                condicion = 1
            WHERE condicion IN (2, 3);
        ');
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
