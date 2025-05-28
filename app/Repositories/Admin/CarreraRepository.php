<?php

namespace App\Repositories\Admin;

use App\Models\Carrera;
<<<<<<< HEAD
=======
use App\Models\CarreraAsignatura;
use App\Models\CarreraAsignaturaProfesor;
>>>>>>> 4e9754a5b53c5dba6abc454a88901906016b97bb
use App\Models\Configuracion;
use PhpParser\Node\Expr\FuncCall;


class CarreraRepository{

    public $config;
    public $availableFiels = ['nombre','asignatura'];

    public function __construct() {
        $this->config = Configuracion::todas();
    }

    function index($request){
        $idsQuery = Carrera::select('carreras.id')
            ->leftJoin('asignaturas','asignaturas.id_carrera', 'carreras.id');

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

        $carreras = Carrera::select('carreras.*')->whereIn('carreras.id', $ids)
        ->orderBy('nombre')
        ->paginate($this->config['filas_por_tabla']);
    }

    public function setAsignatura($asignatura, $carrera){
<<<<<<< HEAD
        // Implement logic to associate asignatura with carrera if needed
        // Example: return $carrera->asignaturas()->attach($asignatura->id);
=======
        $data = [
            'id_asignatura' => $asignatura->id,
            'id_carrera' => $carrera->id
        ];

        CarreraAsignaturaProfesor::updateOrInsert(['id_asignatura' => $asignatura->id], $data);
>>>>>>> 4e9754a5b53c5dba6abc454a88901906016b97bb
    }

public function GETresolucion($carrera)
{
    return Carrera::where('id', $carrera->id)
        ->select('nombre','resolucion', 'vigente', 'resolucion_archivo')
        ->first();
}

}