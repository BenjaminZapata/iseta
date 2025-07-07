<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Cursada;
use App\Models\Egresado;
use App\Services\AlumnoMatriculacionService;
use Illuminate\Http\Request;

class AdminMatriculacionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admin');
    }

    /*
     | ---------------------------------------------
     | Vista de rematriculacion
     | ---------------------------------------------
     */
    function rematriculacion_vista(Request $request,Alumno $alumno, AlumnoMatriculacionService $matriculacionService){

        $carrera = Carrera::where('id', $request->input('carrera'))->first();




        $anotables = $matriculacionService->matriculables($alumno, $carrera);

        return view('Admin.Alumnos.rematriculacion', [
            'asignaturas' => $anotables,
            'carrera'=>$carrera,
            'alumno' => $alumno
        ]);
    }


    /*
     | ---------------------------------------------
     | Post de rematriculacion
     | ---------------------------------------------
     */


     // Falta chequear lo mismo que arriba

    public function rematriculacion(Request $request, Alumno $alumno,Carrera $carrera, AlumnoMatriculacionService $rematService){

        /// Ver que no haya seleccionado mas de 2 libres
        $libres=0;
        foreach ($request->except('_token') as $value) {
            if($value == 1){
                $libres++;
            }
        }
        $inscripcion = Egresado::select('id')
                            ->where('id_carrera', $carrera->id)
                            ->where('id_alumno', $alumno->id)
                            ->first();



        $asignaturas = $rematService->validasParaRegistrar($carrera,$request->except('_token'),$alumno);

        if(!$asignaturas['success'])
            return redirect()->back()->with('error',$asignaturas['mensaje']);
        else
            $asignaturas = $asignaturas['mensaje'];

        // Año de la rematriculacion
        $anio_remat = Configuracion::get('anio_remat');


        // Registrar las cursadas
        foreach($asignaturas as $asigId => $tipoCursada){
            $aprobada=3;
            $tipoCursada = $tipoCursada-1;
            if($tipoCursada==0 || $tipoCursada==2 || $tipoCursada==3){
                $aprobada=1;
            }

            Cursada::create([
                'id_asignatura' => $asigId,
                'id_alumno' => $alumno->id,
                'condicion' => $tipoCursada,
                'aprobada' => $aprobada,
                'anio_cursada' => $anio_remat
            ]);
        }


        return redirect()->back()->with('mensaje','Se ha rematriculado correctamente');
    }

}
