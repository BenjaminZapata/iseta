<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $database = env('DB_DATABASE');

        // 1️⃣ Cambiar charset y collation de la base de datos
        DB::statement("ALTER DATABASE `{$database}` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;");

        // 2️⃣ Generar ALTER TABLE para todas las tablas
        $tables = DB::select('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?', [$database]);

        foreach ($tables as $table) {
            $tableName = $table->TABLE_NAME;
            DB::statement("ALTER TABLE `{$tableName}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
