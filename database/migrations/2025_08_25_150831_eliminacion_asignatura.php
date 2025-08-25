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
        // 🔹 Cambiar id_carrera a nullable en asignaturas
        Schema::table('asignaturas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_carrera')->nullable()->change();
        });

        // 🔹 Modificar FK de carrera_asignatura_profesor con cascade
        Schema::table('carrera_asignatura_profesor', function (Blueprint $table) {
            $table->dropForeign(['id_asignatura']);
            $table->foreign('id_asignatura')
                ->references('id')
                ->on('asignaturas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 🔹 Revertir id_carrera a NOT NULL
        Schema::table('asignaturas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_carrera')->nullable(false)->change();
        });

        // 🔹 Volver a FK sin cascade
        Schema::table('carrera_asignatura_profesor', function (Blueprint $table) {
            $table->dropForeign(['id_asignatura']);
            $table->foreign('id_asignatura')
                ->references('id')
                ->on('asignaturas');
        });
    }
};
