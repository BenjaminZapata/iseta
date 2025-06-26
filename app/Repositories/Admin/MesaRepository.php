<?php

namespace App\Repositories\Admin;

use App\Models\Asignatura;
use App\Models\Configuracion;
use App\Models\Cursada;
use App\Models\Examen;
use App\Models\Mesa;
use App\Models\Profesor;

class MesaRepository
{
    public $config;
    public $availableFiels = ['alumno','carrera','asignatura','profesor'];

    public function __construct() {
        $this->config = Configuracion::todas();
    }

    function index($request){
        $idsQuery = Mesa::select('mesas.id')
            ->leftJoin('carreras', 'carreras.id', 'mesas.id_carrera')
            ->leftJoin('carrera_asignatura_profesor', 'carrera_asignatura_profesor.id_asignatura', 'carreras.id')
            ->leftJoin('examenes','examenes.id_mesa','mesas.id')
            ->leftJoin('alumnos', 'alumnos.id', 'examenes.id_alumno');


        if($request->has('filter_carrera_id') && $request->input('filter_carrera_id') != 0){
            $idsQuery->where('mesas.id_carrera', $request->input('filter_carrera_id'));
            if($request->has('filter_asignatura_id') && $request->input('filter_asignatura_id') != 0){
                $idsQuery->where('mesas.id_asignatura', $request->input('filter_asignatura_id'));
            }
        }

        if($request->has('filter_alumno_id') && $request->input('filter_alumno_id') != 0)
            $idsQuery->where('alumnos.id', $request->input('filter_alumno_id'));

        if($request->has('filter_llamado') && $request->input('filter_llamado') != 0)
            $idsQuery->where('mesas.llamado', $request->input('filter_llamado'));

        if($request->has('filter_presidente') && $request->input('filter_presidente') != 0)
            $idsQuery->where('mesas.prof_presidente', $request->input('filter_presidente'));

        if($request->has('filter_vocal1') && $request->input('filter_vocal1') != 0)
            $idsQuery->where('mesas.prof_vocal_1', $request->input('filter_vocal1'));

        if($request->has('filter_vocal2') && $request->input('filter_vocal2') != 0)
            $idsQuery->where('mesas.prof_vocal_2', $request->input('filter_vocal2'));

        if($request->has('filter_from') && $request->input('filter_from') != 0){
            $idsQuery->whereDate('mesas.fecha', '>=',$request->input('filter_from'));
        }

        if($request->has('filter_to') && $request->input('filter_to') != 0){
            $idsQuery->whereDate('mesas.fecha', '<=',$request->input('filter_to'));
        }




        if($request->has('filter_search_box') && ''!=$request->input('filter_search_box') && in_array($request->input('filter_field'),$this->availableFiels)){
            $word = str_replace(' ','%',$request->input('filter_search_box'));

            if($request->input('filter_field') == 'alumno'){
                $idsQuery->whereRaw("(CONCAT(alumnos.nombre,' ',alumnos.apellido) LIKE '%$word%')");
            }
            else if($request->input('filter_field') == 'profesor'){
                $prof_ids = Profesor::select('id')->whereRaw("(CONCAT(nombre,' ',apellido) LIKE '%$word%')")->get()->pluck('id');
                $idsQuery->whereIn('mesas.prof_presidente',$prof_ids);
            }
            else if($request->input('filter_field') == 'carrera'){
                $idsQuery->where('carreras.nombre','LIKE','%'.$word.'%');
            }
            else if($request->input('filter_field') == 'asignatura'){
                $asig_ids = Asignatura::select('id')->where('nombre','LIKE','%'.$word.'%')->get()->pluck('id');
                $idsQuery->whereIn('mesas.id_asignatura',$asig_ids);
            }else{
                $idsQuery->where($request->input('filter_field'), 'LIKE', '%'.$request->input('filter_search_box').'%');
            }
        }




        //TODO: Buscar mesa futuras (una por asignatura)
        // Paso 1: Obtener IDs de la última mesa futura
    $idsMesas = Mesa::selectRaw('MAX(id) as id')
        ->where('fecha', '>=', now())
        ->groupBy('id_asignatura')
        ->get()
        ->pluck('id');

        // Paso 2: Si no hay futuras, buscar últimas pasadas
    if ($idsMesas->isEmpty()) {
        $idsMesas = Mesa::selectRaw('MAX(id) as id')
            ->where('fecha', '<=', now())
            ->groupBy('id_asignatura')
            ->get()
            ->pluck('id');
    }

        // Paso 3: Traer las mesas con asignatura y nota del examen
    $mesas = Mesa::with(['asignatura'])
        ->leftJoin('asignaturas', 'asignaturas.id', '=', 'mesas.id_asignatura')
        ->leftJoin('examenes', 'examenes.id_mesa', '=', 'mesas.id')
        ->select(
        'mesas.*',
        'asignaturas.nombre as asignatura_nombre',
        'examenes.nota as alumno_nota'
        )
        ->whereIn('mesas.id', $idsMesas)
        ->orderBy('mesas.fecha', 'desc')
        ->orderBy('mesas.llamado', 'asc')
        ->paginate($this->config['filas_por_tabla']);
    return $mesas;

    }

    public function inscribibles($mesaId){
        $mesa = Mesa::find($mesaId)->first();
        if(!$mesa){
            abort(404, 'Mesa no encontrada');
        }

        $inscribiblesCursada = Cursada::select('cursadas.*') // Evita ambigüedad si hay campos con el mismo nombre
            ->join('alumnos', 'cursadas.id_alumno', '=', 'alumnos.id')
            ->where(function($query) {
                $query->where('cursadas.aprobada', 1)
                    ->orWhereIn('cursadas.condicion', [0, 2, 3]);
            })
            ->where('cursadas.id_asignatura', $mesa->id_asignatura)
            ->orderBy('alumnos.apellido')
            ->orderBy('alumnos.nombre')
            ->get();

            $inscribibles=[];

        foreach ($inscribiblesCursada as $cursada) {
            $alumno = $cursada->alumno;

            $examen = Examen::where('id_alumno', $alumno->id)
                ->where(function($query) use($mesa){
                    $query->where('nota','>=',4)
                        ->orWhere('id_mesa', $mesa->id);
                })
                ->where('id_asignatura', $mesa->id_asignatura)
                ->first();

            if(!$examen){
                $inscribibles[]=$alumno;
            }
        }
        return $inscribibles;
    }

    public function delete($mesa){
        Examen::where('id_mesa',$mesa->id)->where('nota',0)->delete();
        $mesa->delete();
    }
}
