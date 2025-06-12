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
        $this -> middleware('auth:admin');
    }

    public function agregar(Request $request, Asignatura $asignatura){

        /**
         * $asignatura = asignatura a la que se le agrega la correlativa, ej, Ingles 2.
         * $asigCorrelativa = la asignatura que se agrega como correlativa, ej, Ingles 1.
         */

        $asigCorrelativa = Asignatura::find($request->input('id_asignatura'));

        if($asignatura->anio == 1) // las asignaturas del primer año no tienen correlativas
            return redirect()->back()->with('error', 'No puedes añadir correlativas en asignaturas del primer año');

        if($asignatura->anio < $asigCorrelativa->anio) // una asig del 2do año, no puede tener una correlativa de 1er año ni 2do
            return \redirect()->back()->with('error','El año de la correlativa debe ser menor al de la asignatura');

        if($asignatura->tieneLaCorrelativa($asigCorrelativa->id))  // Comprobar si ya tienes esa correlativa
            return \redirect()->back()->with('error','Esta asignatura ya tiene esta correlativa');

        Correlativa::create([
            'id_asignatura' => $asignatura->id,
            'asignatura_correlativa' => $asigCorrelativa->id
        ]);

        return redirect()->back()->with('mensaje','Se agrego la correlativa');
    }

    public function eliminar(Request $request, Asignatura $asignatura){

        $correlativa = Correlativa::where('id_asignatura', $asignatura->id)
            ->where('asignatura_correlativa', $request->asignatura_correlativa)
            ->first();

        $correlativa->delete();
        return redirect()->back()->with('mensaje','Se elimino la correlativa');
    }
}
