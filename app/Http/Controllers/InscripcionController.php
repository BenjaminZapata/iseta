<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Examen;
use App\Models\Mesa;
use App\Repositories\Admin\InscripcionRepository;
use App\Services\AlumnoInscripcionService;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    public $inscripcionService;
    public $inscripcionRepo;

    public function __construct(AlumnoInscripcionService $alumnoInscripcionService,InscripcionRepository $inscripcionRepo)
    {

        $this->inscripcionService = $alumnoInscripcionService;
        $this->inscripcionRepo = $inscripcionRepo;

        $this -> middleware('auth:web');
        $this -> middleware('verificado')->only([
            'inscribirse',
            'bajarse',
        ]);
    }

     /*
     | ---------------------------------------------
     | Vista para inscribirse o bajarse a una mesa
     | ---------------------------------------------
     */

     function inscripciones(){

        $disponibles = $this->inscripcionService->inscribiblesDelAlumno(Auth::user());
        return view('Alumnos.Datos.inscripciones', compact('disponibles'));
    }

    /*
     | ---------------------------------------------
     | Solicitud de inscripcion a mesa [post]
     | ---------------------------------------------
     */

    function inscribirse(Request $request){

        if(!Configuracion::get('alumno_puede_anotarse_mesa')){
            return redirect()->back()->with('error', 'El administrador ha desactivado esta caracteristica');
        }

        if(!$request->has('mesa')) return redirect()->back()->with('error','Selecciona una mesa');

        $mesa = Mesa::with('asignatura','anotado')->find($request->input('mesa'));

        $puede = $this->inscripcionService->puedeInscribirse($mesa, Auth::user());
        if(!$puede['success']) return redirect()->back()->with('error', $puede['mensaje']);

        if($mesa->llamado == 2){
            $rindioLlamado1 = $this->inscripcionService->rindioLlamado1($mesa,Auth::user());
            if($rindioLlamado1){
                return redirect()->back()->with('error','Ya has rendido el llamado 1');
            }
        }

        $this->inscripcionRepo->crearInscripcion($mesa, Auth::user());

        return redirect()->route('alumno.inscripciones')->with('mensaje', 'Te has anotado a la mesa');
    }

    /*
     | ---------------------------------------------
     | Solicitud para bajarse de una mesa [post]
     | ---------------------------------------------
     */

    public function bajarse(Request $request){

        if(!Configuracion::get('alumno_puede_bajarse_mesa')){
            return redirect()->back()->with('error', 'El administrador ha desactivado esta caracteristica');
        }

        if(!$request->has('mesa')) return redirect()->back()->with('error','Selecciona una mesa');

        $mesa = Mesa::find($request->mesa);

        if(!$mesa) return redirect()->back()->with('error','No se encontro la mesa');

        $examen = Examen::select('id')
            -> where('id_mesa', $mesa->id)
            -> where('id_alumno', Auth::id())
            -> first();

        $puede = $this->inscripcionService->puedeBajarse($mesa, $examen);
        if(!$puede['success']) return redirect()->back()->with('error', $puede['mensaje']);

        $this->inscripcionRepo->borrar($examen);

        return redirect()->back()->with('mensaje','Te has dado de baja de la mesa.');
    }

}
