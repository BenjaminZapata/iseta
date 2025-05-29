<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Correlativa;
use App\Models\Cursada;
use App\Services\AlumnoMatriculacionService;
use App\Services\DiasHabiles;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class RematriculacionController extends Controller
{
    public $rematService;

    public function __construct(AlumnoMatriculacionService $service)
    {
        $this -> rematService = $service;

        $this -> middleware('auth:web');
        $this -> middleware('verificado')->only([
            'rematriculacion',
            'bajar_rematriculacion'
        ]);
    }

     /*
     | ---------------------------------------------
     | Vista de rematriculacion
     | ---------------------------------------------
     */
    function rematriculacion_vista(Request $request){
        if(!Configuracion::get('alumno_puede_anotarse_cursada')){
            return redirect()->route('alumno.info')->with('error', 'El administrador ha desactivado la inscripcion a cursadas');
        }
        $carrera = Carrera::getDefault();
        $esFechaDeRemat = $this->rematService->esFechaDeRematriculacion();

        if(!$esFechaDeRemat) return redirect()->back()->with('aviso','Aun no es fecha de rematriculacion');

        $anotables = $this->rematService->matriculables(Auth::user(), $carrera);

        return view('Alumnos.datos.rematriculacion', [
            'asignaturas' => $anotables,
            'carrera'=>$carrera->id,
        ]);
    }


    /*
     | ---------------------------------------------
     | Post de rematriculacion
     | ---------------------------------------------
     */


    public function rematriculacion(Request $request, Carrera $carrera){

        if(!Configuracion::get('alumno_puede_anotarse_cursada')){
            return redirect()->back()->with('error', 'El administrador ha desactivado esta caracteristica');
        }

        if(!$carrera->estaInscripto()){
            return redirect()->back()->with('error','No estas inscripto en esta carrera');
        }


        // Ver que no haya seleccionado mas de 2 libres
        $libres=0;
        foreach ($request->except('_token') as $value) {
            if($value == 1){
                $libres++;
            }
        }

        if($libres>0 && !Configuracion::get('alumno_puede_anotarse_libre')){
            return redirect()->back()->with('error', 'El administrador no permite inscripciones como libres');
        }

        $asignaturas = $this->rematService->validasParaRegistrar($carrera,$request->except('_token'),Auth::user());

        if(!$asignaturas['success']) return redirect()->back()->with('error',$asignaturas['mensaje']);
        else $asignaturas = $asignaturas['mensaje'];

        // Año de la rematriculacion
        $anio_remat = Configuracion::get('anio_remat');

        // Registrar las cursadas
        foreach($asignaturas as $asigId => $tipoCursada){
            Cursada::create([
                'id_asignatura' => $asigId,
                'id_alumno' => Auth::id(),
                'condicion' => $tipoCursada-1,
                'aprobada' => $tipoCursada==1? 1:3,
                'anio_cursada' => $anio_remat
            ]);
        }

        return redirect()->back()->with('mensaje','Te has rematriculado correctamente');
    }




    function bajar_rematriculacion(Request $request, Cursada $cursada){

        if(!Configuracion::get('alumno_puede_bajarse_cursada')){
            return redirect()->back()->with('error', 'El administrador ha desactivado esta caracteristica');
        }


        if(!$cursada) return redirect()->route('alumno.inscripciones')->with('error','No se encontro la cursada');

        // Es su cursada
        if(!Gate::allows('delete-cursada', $cursada)){
            return \redirect()->back()->with('error', 'Esta cursada no te pertenece... &#129320;');
        }

        // si es regular y tiene nota, ya la curso!
        if($cursada->aprobada != 3 && $cursada->condicion!=0) return redirect()->back()->with('error','Ya has terminado de cursar');

        $config = Configuracion::todas();

        // Si aun no e termino el limite de tiempo para bajarse
        if(DiasHabiles::desdeHoyHasta($config['fecha_limite_desrematriculacion'])<=0){
            return redirect()->back()->with('error','Ya ha caducado el tiempo para desmatricularse');
        }

        if($cursada->anio_cursada != $config['anio_remat']){
            return redirect()->back()->with('error','No puedes bajarte de esta cursada porque es del año anterior');
        }

        $fechaCursada = new DateTime($cursada->created_at);
        $fechaLimite = new DateTime($config['fecha_limite_desrematriculacion']);

        if(isset($cursada->created_at) && $fechaCursada>$fechaLimite){
            return redirect()->back()->with('error','Ya estas cursando esta asignatura');
        }

        $cursada->delete();
        return redirect()->back()->with('mensaje','Se ha eliminado la cursada');
    }
}
