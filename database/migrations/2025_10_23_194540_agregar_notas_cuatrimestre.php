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
        Schema::table('cursadas', function (Blueprint $table) {
            $table->tinyInteger('primer_cuatrimestre_nota')->nullable(true);
            $table->tinyInteger('segundo_cuatrimestre_nota')->nullable(true);
            $table->tinyText('observaciones')->nullable(true);
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
