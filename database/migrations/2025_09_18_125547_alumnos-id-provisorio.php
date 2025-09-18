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
        Schema::table("alumnos", function (Blueprint $table) {
            $table->ulid("id_provisorio")
                ->unique("alumnos_id_provisorio_unique")
                ->after("id")
                ->nullable()
                ->invisible();
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
