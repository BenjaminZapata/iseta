<?php

namespace App\Repositories\Admin;

use App\Models\Configuracion;
use App\Models\Cursada;
use App\Models\Examen;
use App\Models\Mesa;

class CursadaRepository
{
    public $config;
public $availableFiels = ['anio_cursada'];

    public function __construct() {
        $this->config = Configuracion::todas();
    }

    function index($request){
        $idsQuery = Cursada::select('cursadas.id')
            ->leftJoin('asignaturas', 'asignaturas.id', 'cursadas.id_asignatura')
            ->leftJoin('alumnos', 'alumnos.id', 'cursadas.id_alumno')
            ->leftJoin('carreras', 'carreras.id', 'asignaturas.id_carrera');

        if($request->has('filter_carrera_id') && $request->input('filter_carrera_id') != 0){
            $idsQuery->where('carreras.id', $request->input('filter_carrera_id'));
            if($request->has('filter_asignatura_id') && $request->input('filter_asignatura_id') != 0){
                $idsQuery->where('cursadas.id_asignatura', $request->input('filter_asignatura_id'));
            }
        }

        if($request->has('filter_alumno_id') && $request->input('filter_alumno_id') != 0)
            $idsQuery->where('alumnos.id', $request->input('filter_alumno_id'));

        if($request->has('filter_condicion') && $request->input('filter_condicion') != 0)
            $idsQuery->where('cursadas.condicion', $request->input('filter_condicion')-1);

        if($request->has('filter_aprobada') && $request->input('filter_aprobada') != 0)
            $idsQuery->where('cursadas.aprobada', $request->input('filter_aprobada'));



            if($request->has('filter_search_box') && ''!=$request->input('filter_search_box') && in_array($request->input('filter_field'),$this->availableFiels)){
                $idsQuery->where($request->input('filter_field'), 'LIKE', '%'.$request->input('filter_search_box').'%');
        }

        $ids = $idsQuery->distinct()->get()->pluck('id');

        $cursadas = Cursada::with('alumno','asignatura')->whereIn('cursadas.id', $ids)
        ->orderBy('anio_cursada', 'DESC')
        ->paginate($this->config['filas_por_tabla']);


        return $cursadas;

    }
}
