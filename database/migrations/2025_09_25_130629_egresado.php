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
        Schema::table('egresadoinscripto', function (Blueprint $table) {
            if (! Schema::hasColumn('egresadoinscripto', 'estado')) {
                $table->tinyInteger('estado')->default(0);
            }
            $table->foreign('id_alumno')->references('id')->on('alumnos');
            $table->foreign('id_carrera')->references('id')->on('carreras');
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
