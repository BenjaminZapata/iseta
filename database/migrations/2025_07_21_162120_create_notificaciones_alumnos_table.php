<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notificaciones_alumnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alumno')->constrained('alumnos')->onDelete('cascade');
            $table->string('tipo');
            $table->string('mensaje');
            $table->boolean('vista')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones_alumnos');
    }
};
