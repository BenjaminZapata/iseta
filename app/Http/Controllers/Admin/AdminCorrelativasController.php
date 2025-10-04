<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Correlativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminCorrelativasController extends Controller
{
    public function __construct()
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
        foreach ($data as $correlativa) {
            Log::debug($correlativa);
            Correlativa::create([
                'id_asignatura' => $asignatura->id,
                'id_asignatura_correlativa' => $correlativa['id'],
                'id_carrera' => $carrera->id,
            ]);
        }

        return redirect()->back()->with('mensaje', 'Se agrego la correlativa');
    }

    public function eliminar(Request $request, Asignatura $asignatura)
    {

        $correlativa = Correlativa::where('id_asignatura', $asignatura->id)
            ->where('asignatura_correlativa', $request->asignatura_correlativa)
            ->first();

        $correlativa->delete();

        return redirect()->back()->with('mensaje', 'Se elimino la correlativa');
    }
}
