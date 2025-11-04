<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixExamenesSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 1. Aprobado (1) se mantiene igual
        DB::table('examenes')
            ->where('aprobado', 1)
            ->update(['aprobado' => 1]);

        // 🔹 2. Desaprobado (2) pasa a 0
        DB::table('examenes')
            ->where('aprobado', 2)
            ->update(['aprobado' => 0]);

        // 🔹 3. Ausente (3) → nota 0, aprobado null
        DB::table('examenes')
            ->where('aprobado', 3)
            ->update([
                'nota' => 0,
                'aprobado' => null,
                'asistencia' => 0,
            ]);

        // 🔹 4. Si tiene nota mayor a 0 → asistencia = 1 (Presente)
        DB::table('examenes')
            ->where('nota', '>', 0)
            ->update(['asistencia' => 1]);

        // 🔹 5. Si nota es 0 o null → asistencia = 0 (Ausente)
        DB::table('examenes')
            ->where(function ($q) {
                $q->where('nota', 0)
                  ->orWhereNull('nota');
            })
            ->update(['asistencia' => 0]);

        $this->command->info('✅ Datos de examenes actualizados: aprobado y asistencia normalizados.');
    }
}
