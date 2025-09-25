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
        Schema::table('asignaturas', function (Blueprint $table) {
            $table->integer('cantidad_modulo')->default(0);
            $table->integer('carga_horaria')->nullable()->change();
            $table->integer('anio')->nullable()->change();
            $table->dropColumn('id_carrera');
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