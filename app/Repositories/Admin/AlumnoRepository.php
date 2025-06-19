<?php

namespace App\Repositories\Admin;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Configuracion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AlumnoRepository{

    public $config;
    public $availableFiels = ['alumno','dni','email','ciudad','telefono1'];

    public function __construct() {
        $this->config = Configuracion::todas();
    }

    function index($request){
        $idsQuery = Alumno::select('alumnos.id')
        ->leftJoin('egresadoinscripto', 'egresadoinscripto.id_alumno', '=', 'alumnos.id')
        ->leftJoin('carreras','carreras.id', '=', 'egresadoinscripto.id_carrera');

        if($request->has('filter_carrera_id') && $request->input('filter_carrera_id') != 0){
            $idsQuery->where('egresadoinscripto.id_carrera', $request->input('filter_carrera_id'));
        }

        if($request->has('filter_ciudad') && $request->input('filter_ciudad') != 0){
            $idsQuery->where('alumnos.ciudad', $request->input('filter_ciudad'));
        }

        if($request->has('filter_estado_civil') && $request->input('filter_estado_civil') != 0){
           $idsQuery->where('alumnos.estado_civil', $request->input('filter_estado_civil'));
        }

        if($request->has('filter_search_box') && ''!=$request->input('filter_search_box') && in_array($request->input('filter_field'),$this->availableFiels)){
            if($request->input('filter_field') == 'alumno'){
                $word = str_replace(' ','%',$request->input('filter_search_box'));
                $idsQuery->whereRaw("(CONCAT(alumnos.nombre,' ',alumnos.apellido) LIKE '%$word%' OR (CONCAT(alumnos.apellido,' ',alumnos.nombre) LIKE '%$word%'))");
            }else{
                $idsQuery->where($request->input('filter_field'), 'LIKE', '%'.$request->input('filter_search_box').'%');
            }

        }

        $ids = $idsQuery->distinct()->get()->pluck('id');

        $query = Alumno::select('alumnos.*')
            ->whereIn('alumnos.id', $ids);

        $query->orderBy('apellido')->orderBy('nombre');
        return $query->paginate($this->config['filas_por_tabla']);
    }

    // agregar una institucion secundaraia a un alumno
    public function agregarInstitucionSecundaria(string $nombre): Alumno
    {
        return Alumno::create([
            'nombre_institucion_secundaria' => $nombre
        ]);
    }

    //actualizr una institucion secundaria de un alumno
    public function actualizarInstitucionSecundaria(int $id, string $nuevoNombre): ?Alumno
    {
        $alumno = Alumno::query()->find($id);
        if (!$alumno){
            return null;
        }

        $alumno->nombre_institucion_secundaria = $nuevoNombre;
        $alumno->save();

        return $alumno;
    }
}
