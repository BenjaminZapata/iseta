<?php

namespace App\Services\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Correlativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psy\Readline\Hoa\Console;

class AdminCorrelativasService
{
    public function agregar(Asignatura $asignatura, array $correlativas, int $carreraId)
    {
        if (empty($correlativas)) {
            return flash()
                ->option('position', 'top-center')
                ->error('Seleccione una correlativa');
        }
        foreach ($correlativas as $correlativa) {
            $asignatura->correlativas()->attach($correlativa['id'], ['id_carrera' => $carreraId]);
        }
        return flash()
                ->option('position', 'top-center')
                ->success('Se agregaron las correlativas');
    }

    public function eliminar(int $carreraId, Asignatura $asignatura, int $correlativaId)
    {

        $asignatura->correlativas()
            ->wherePivot('id_asignatura_correlativa', $correlativaId)
            ->wherePivot('id_carrera', $carreraId)
            ->detach($correlativaId);
        return true;
            
    }
}