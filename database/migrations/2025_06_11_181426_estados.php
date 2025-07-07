<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class estados extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('egresadoinscripto', function (Blueprint $table) {
        $table->integer('estado')->default(0);
    });

    // Agregá este delay para asegurarte que la columna fue creada antes del update (opcional)
    DB::statement('SET SESSION sql_mode = ""'); // Por si tenés modo estricto

    // Ahora hacé el update
    DB::table('egresadoinscripto')->whereNotNull('anio_finalizacion')->update(['estado' => 1]);
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
