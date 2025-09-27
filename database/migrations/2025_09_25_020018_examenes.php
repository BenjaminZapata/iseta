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
        Schema::table("examenes", function (Blueprint $table) {
            $table->integer("id_carrera")->nullable();
            $table->foreign("id_carrera")->references("id")->on("carreras");
        });
        DB::statement("
            UPDATE examenes e
            JOIN asignaturas a ON e.id_asignatura = a.id
            SET e.id_carrera = a.id_carrera
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};