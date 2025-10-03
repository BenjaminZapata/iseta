<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Correlativa;
use Illuminate\Http\Request;

class AdminCorrelativasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function agregar(Request $request, Asignatura $asignatura)
    {

        /**
         * $asignatura = asignatura a la que se le agrega la correlativa, ej, Ingles 2.
         * $asigCorrelativa = la asignatura que se agrega como correlativa, ej, Ingles 1.
         */
        $asigCorrelativa = $this->validate($request);
        Correlativa::create([
            'id_asignatura' => $asignatura->id,
            'asignatura_correlativa' => $asigCorrelativa->id,
        ]);

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
