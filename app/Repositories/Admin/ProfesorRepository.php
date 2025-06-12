<?php

namespace App\Repositories\Admin;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Profesor;

class ProfesorRepository{

    public $config;
    public $availableFiels = ['profesor','dni','email','ciudad','telefono1'];

    public function __construct() {
        $this->config = Configuracion::todas();
    }

    function index($request){
        $idsQuery = Profesor::select('profesores.id');

        if($request->has('filter_carrera_id') && $request->input('filter_carrera_id') != 0){
            $idsQuery->where('egresadoinscripto.id_carrera', $request->input('filter_carrera_id'));
        }

        if($request->has('filter_search_box') && ''!=$request->input('filter_search_box') && in_array($request->input('filter_field'),$this->availableFiels)){
            if($request->input('filter_field') == 'profesor'){
                $word = str_replace(' ','%',$request->input('filter_search_box'));
                $idsQuery->whereRaw("(CONCAT(profesores.nombre,' ',profesores.apellido) LIKE '%$word%' OR profesores.email  LIKE '%$word%')");
            }else{
                $idsQuery->where($request->input('filter_field'), 'LIKE', '%'.$request->input('filter_search_box').'%');
            }

        }

        $ids = $idsQuery->distinct()->get()->pluck('id');

        $query = Profesor::select('profesores.*')
            ->whereIn('profesores.id', $ids);

        // ✅ ORDENAMIENTO FIJO POR APELLIDO Y NOMBRE
        $query->orderBy('apellido')->orderBy('nombre');

        return $query->paginate($this->config['filas_por_tabla']);

    }

}
