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
            $table->integer("id_carrera");
            $table->foreign("id_carrera")->references("id")->on("carreras");
        });
        DB::table('examenes')
        ->join('asignaturas', 'examenes.id_asignatura', '=', 'asignaturas.id')
        ->update(['examenes.id_carrera' => 'asignaturas.id_carrera']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};