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
            $table->integer('estado')->default(0);
        });

        // Ahora hacé el update
        DB::table('egresadoinscripto')->whereNotNull('anio_finalizacion')->update(['estado' => 1]);

        Schema::table('egresadoinscripto', function (Blueprint $table) {
            $table->integer('id_carrera');
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