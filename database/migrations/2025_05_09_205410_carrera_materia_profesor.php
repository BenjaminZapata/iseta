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
        Schema::create('carrera_asignatura_profesor', function (Blueprint $table) {
            $table->integer('id_carrera');
            $table->integer('id_asignatura');
            $table->integer('id_profesor')->nullable(true);

            $table->primary(['id_carrera', 'id_asignatura']);

            $table->foreign('id_carrera')->references('id')->on('carreras')->onDelete('cascade');
            $table->foreign('id_asignatura')->references('id')->on('asignaturas')->onDelete('cascade');
            $table->foreign('id_profesor')->references('id')->on('profesores');

            $table->timestamps();
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
