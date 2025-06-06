<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Egresado;
use App\Models\Examen;
use App\Repositories\AlumnoDataRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AlumnoController extends BaseController
{
    public $alumnoRepository;
    public $defaultFilters = ['filter_carrera' => 10];

    public function __construct(AlumnoDataRepository $alumnoDataRepository)
    {
        parent::__construct();

        $this->alumnoRepository = $alumnoDataRepository;

        $this -> middleware('auth:web');

        $this -> middleware('verificado')->only([
            'info',
            'setCarreraDefault'
        ]);
    }

    /*
     | -------------------------------------------
     | informacion genereal del alumno
     | y un select para elegir una carrera default
     |  ---------------------------------------------
     */

    function info(){
        return view('Alumnos.Datos.informacion', [
            'alumno'=>Auth::user(),
            'default' => Carrera::getDefault()
        ]);
    }

    /*
     | ----------------------------------------------------------------------------
     | Setea la carrera que el alumno haya elegido como default para los resultados
     | se guarda en la tabla: carreras_default
     | ----------------------------------------------------------------------------
     */

     function setCarreraDefault(Request $request){

        if(!Egresado::estaInscripto($request->input('carrera'))){
            return redirect()->back()->with('error','No estas inscripto en esta carrera');
        }

        $this->alumnoRepository->setCarreraDefault(Auth::id(),$request->carrera);
        return redirect()->back()->with('mensaje','Se ha seleccionado la carrera');
    }

    /*
     | ---------------------------------------------
     | ver todas las cursadas del alumno y su estado
     | ---------------------------------------------
     */

    function cursadas(Request $request){
        $filtro = $request->filtro ? $request->filtro: '';
        $campo = $request->campo ? $request->campo: '';
        $orden = $request->orden ? $request->orden: 'fecha';

        // cursadas del alumno de la carrera seleccionada
        $cursadas = $this->alumnoRepository->cursadas($filtro, $campo, $orden);

        // lista de examenes aprobados para saber si una cursada tiene rendido su final
        $examenesAprobados = Examen::select('examenes.id_asignatura')
            -> where('examenes.nota','>=',4)
            -> where('examenes.id_alumno',Auth::id())
            -> orderBy('examenes.id_asignatura')
            -> get()-> pluck('id_asignatura')-> toArray();

        return view('Alumnos.Datos.cursadas', [
            'cursadas' => $cursadas,
            'examenesAprobados' => $examenesAprobados,
            'puedeBajarse' => Configuracion::puedeDesinscribirCursada(),
            'filtros'=>[
                'campo' => $campo,
                'orden' => $orden,
                'filtro' => $filtro
            ]
        ]);
    }

    /**
     * Examenes rendidos por el alumno
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function examenes(Request $request){

        $filtro = $request->filtro ? $request->filtro: '';
        $campo = $request->campo ? $request->campo: '';
        $orden = $request->orden ? $request->orden: 'fecha';

        $examenes = $this->alumnoRepository->examenes($filtro,$campo,$orden);

        return view('Alumnos.Datos.examenes', [
            'examenes'=>$examenes,
            'filtros'=>[
                'campo' => $campo,
                'orden' => $orden,
                'filtro' => $filtro
            ]
        ]);
    }

}
