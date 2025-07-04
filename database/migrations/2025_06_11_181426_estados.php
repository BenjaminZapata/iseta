<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class estados extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
           Schema::table('egresadoinscripto', function (Blueprint $table){
            $table->tinyInteger('estado')->required();

                    // Actualizar los registros existentes después de agregar la columna
        DB::table('egresadoinscripto')
            ->whereNotNull('anio_finalizacion')
            ->update(['estado' => 1]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('egresadoinscripto', function (Blueprint $table){
            if(Schema::hasColumn('egresadoinscripto', 'estado')) {
                $table->dropColumn('estado');
            }
        });
    }
};
