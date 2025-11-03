<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VincularPresidentesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run()
    {
        $mesas = DB::table('mesas')
            ->select('id_carrera', 'id_asignatura', 'prof_presidente')
            ->whereNotNull('prof_presidente')
            ->where('prof_presidente', '!=', 0)
            ->get();

       foreach ($mesas as $mesa) {
    $carreraId    = $mesa->id_carrera;
    $asignaturaId = $mesa->id_asignatura;
    $profesorId   = $mesa->prof_presidente;

    if ($profesorId == 0) continue;

    // Buscar fila existente con profesor en NULL
    $registro = DB::table('carrera_asignatura_profesor')
        ->where('id_carrera', $carreraId)
        ->where('id_asignatura', $asignaturaId)
        ->whereNull('id_profesor')
        ->first();

    // Si existe y está vacío, actualizar
    if ($registro) {
        DB::table('carrera_asignatura_profesor')
            ->where('id_carrera', $carreraId)
            ->where('id_asignatura', $asignaturaId)
            ->whereNull('id_profesor')
            ->update([
                'id_profesor' => $profesorId,
            ]);
    }
}

    }
}
