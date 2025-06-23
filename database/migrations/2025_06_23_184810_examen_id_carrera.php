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
        Schema::table('examenes', function (Blueprint $table) {
            $table->integer('id_carrera')->nullable(true);
            $table->foreign('id_carrera')->references('id')->on('carreras');
        });

        DB::table('mesas')
            ->whereNotNull('id_carrera')
            ->get()
            ->each(function ($mesa) {
                DB::table('examenes')
                    ->where('id_mesa', $mesa->id) // <- asegurás coincidencia por mesa
                    ->update([
                        'id_carrera' => $mesa->id_carrera
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
