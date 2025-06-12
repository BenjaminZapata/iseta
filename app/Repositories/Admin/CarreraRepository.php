<?php

namespace App\Repositories\Admin;

use App\Models\Carrera;

use App\Models\CarreraAsignatura;
use App\Models\CarreraAsignaturaProfesor;
use App\Models\Configuracion;
use PhpParser\Node\Expr\FuncCall;


class CarreraRepository{

    public $config;
    public $availableFiels = ['nombre','asignatura'];

    public function __construct() {
        $this->config = Configuracion::todas();
    }

    public function index($request){
        $idsQuery = Carrera::with('asignaturas');

        if($request->has('filter_vigente') && $request->input('filter_vigente') != 0){
            $value = $request->input('filter_vigente');
            $idsQuery->where('carreras.vigente', $value-1);
        }

        if($request->has('filter_search_box') && ''!=$request->input('filter_search_box') && in_array($request->input('filter_field'),$this->availableFiels)){
            $word = str_replace(' ','%',$request->input('filter_search_box'));
            if($request->input('filter_field') == 'asignatura'){
                $idsQuery->where('asignaturas.nombre','LIKE','%'.$word.'%');
            }else{
                $idsQuery->where('carreras.'.$request->input('filter_field'), 'LIKE', '%'.$request->input('filter_search_box').'%');
            }
        }

        $ids = $idsQuery->distinct()->get()->pluck('id');

<<<<<<< HEAD
        $carreras = Carrera::select('carreras.*')->whereIn('carreras.id', $ids)
        ->orderBy('vigente')
=======
        return Carrera::select('carreras.*')->whereIn('carreras.id', $ids)
>>>>>>> 94e90b2f667dbf1ce9dc40b62a48bd3657d5630b
        ->orderBy('nombre')
        ->paginate($this->config['filas_por_tabla']);
    }

    public function setAsignatura($asignatura, $carrera){
        // Implement logic to associate asignatura with carrera if needed
        // Example: return $carrera->asignaturas()->attach($asignatura->id);
    }

public function GETresolucion($carrera)
{
    return Carrera::where('id', $carrera->id)
        ->select('nombre','resolucion', 'vigente', 'resolucion_archivo')
        ->first();
}

}