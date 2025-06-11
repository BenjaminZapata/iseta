<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNombreInstitucionSecundarioToAlumnosTable extends Migration
{
    public function up()
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->string('nombre_institucion_secundario')->nullable();
        });
    }

    public function down()
{
    Schema::table('alumnos', function (Blueprint $table) {
        if (Schema::hasColumn('alumnos', 'nombre_institucion_secundario')) {
            $table->dropColumn('nombre_institucion_secundario');
        }
    });
}

}

