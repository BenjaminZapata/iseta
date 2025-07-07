<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;
use App\Models\Egresado;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Selection;

class ActualizarEstadoSeeder extends Seeder
{
    public function run(): void
    {
        // si anio_finalizacion tiene valor, entonces se le agrega un valor a la columna estado 
        Egresado:: whereNotNull('anio_finalizacion')->update([
            'estado'=> '1'
        ]);
    }
}