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
           Schema::table('alumnos', function (Blueprint $table){
            $table->enum('estado',['Cursando','Egresado','Desertor'])->required();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table){
            if(Schema::hasColumn('alumnos', 'estado')) {
                $table->dropColumn('estado');
            }
        });
    }
};
