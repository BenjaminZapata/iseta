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
        Schema::table('alumnos', function (Blueprint $table) {
            $table->tinyInteger('estado')->default(0);
            $table->string('lugar_nacimiento')->nullable();
            $table->tinyInteger('genero')->nullable();
            $table->tinyInteger('titulo_secundario')->default(0);
            $table->date('fecha_titulo_secundario')->nullable()->after('titulo_secundario')->default(Carbon::now());
            $table->string('nombre_institucion_secundario')->nullable();
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