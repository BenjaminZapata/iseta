<?php

namespace App\Repositories\Admin;

use App\Models\Carrera;
use App\Models\CarreraAsignatura;
use App\Models\CarreraAsignaturaProfesor;
use App\Models\Configuracion;


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

        return Carrera::select('carreras.*')->whereIn('carreras.id', $ids)
        ->orderBy('nombre')
        ->paginate($this->config['filas_por_tabla']);
    }

    public function setAsignatura($asignatura, $carrera){
        $data = [
            'id_asignatura' => $asignatura->id,
            'id_carrera' => $carrera->id
        ];

        CarreraAsignaturaProfesor::updateOrInsert(['id_asignatura' => $asignatura->id], $data);
    }
}
