<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Correlativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psy\Readline\Hoa\Console;
use App\Services\Admin\AdminCorrelativasService;

class AdminCorrelativasController extends Controller
{
    public function __construct(protected AdminCorrelativasService $adminCorrelativas)
    {
        $this->middleware('auth:admin');
    }

    public function agregar(Request $request, Carrera $carrera, Asignatura $asignatura)
    {
        $data = $request->all();
        $data = json_decode($data['correlativas'], true);
        /**
         * $asignatura = asignatura a la que se le agrega la correlativa, ej, Ingles 2.
         * $asigCorrelativa = la asignatura que se agrega como correlativa, ej, Ingles 1.
         */
        if ($this->adminCorrelativas->agregar($data,$carrera,$asignatura)) {
            return redirect()->back()->with('error', 'No se pudieron agregar las correlativas');
        }

        return redirect()->back()->with('mensaje', 'Se agregaron las correlativas');
    }

    public function eliminar(Request $request)
    {
        $data = $request->all();
        $asignatura = new Asignatura($data['asignatura']);
        $carrera = new Carrera($data['carrera']);
        $response = $asignatura->correlativas()
            ->wherePivot('id_asignatura_correlativa', $data['correlativa'])
            ->wherePivot('id_carrera', $carrera->id)
            ->detach($data['correlativa']);
        Log::debug("algo diferente");
        Log::debug($response);
    }
}