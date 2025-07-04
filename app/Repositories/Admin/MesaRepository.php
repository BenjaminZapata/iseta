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

   public function index($request)
{
    // Inicia el query base con relaciones necesarias
    $query = Mesa::with('asignatura')
        ->leftJoin('carreras', 'carreras.id', 'mesas.id_carrera')
        ->leftJoin('examenes', 'examenes.id_mesa', 'mesas.id')
        ->leftJoin('alumnos', 'alumnos.id', 'examenes.id_alumno');

    // Filtro por carrera
    if ($request->filled('filter_carrera_id') && $request->input('filter_carrera_id') != 0) {
        $query->where('mesas.id_carrera', $request->input('filter_carrera_id'));
    }

    // Filtro por asignatura
    if ($request->filled('filter_asignatura_id') && $request->input('filter_asignatura_id') != 0) {
        $query->where('mesas.id_asignatura', $request->input('filter_asignatura_id'));
    }

    // Filtro por alumno
    if ($request->filled('filter_alumno_id') && $request->input('filter_alumno_id') != 0) {
        $query->where('alumnos.id', $request->input('filter_alumno_id'));
    }

    // Filtro por llamado
    if ($request->filled('filter_llamado') && $request->input('filter_llamado') != 0) {
        $query->where('mesas.llamado', $request->input('filter_llamado'));
    }

    // Filtro por presidente y vocales
    if ($request->filled('filter_presidente') && $request->input('filter_presidente') != 0) {
        $query->where('mesas.prof_presidente', $request->input('filter_presidente'));
    }

    if ($request->filled('filter_vocal1') && $request->input('filter_vocal1') != 0) {
        $query->where('mesas.prof_vocal_1', $request->input('filter_vocal1'));
    }

    if ($request->filled('filter_vocal2') && $request->input('filter_vocal2') != 0) {
        $query->where('mesas.prof_vocal_2', $request->input('filter_vocal2'));
    }

    // Filtro por rango de fechas
    if ($request->filled('filter_from')) {
        $query->whereDate('mesas.fecha', '>=', $request->input('filter_from'));
    }

    if ($request->filled('filter_to')) {
        $query->whereDate('mesas.fecha', '<=', $request->input('filter_to'));
    }

    // Búsqueda general (alumno, profesor, carrera o asignatura)
    if ($request->filled('filter_search_box') && in_array($request->input('filter_field'), $this->availableFiels)) {
        $word = str_replace(' ', '%', $request->input('filter_search_box'));

        switch ($request->input('filter_field')) {
            case 'alumno':
                $query->whereRaw("CONCAT(alumnos.nombre, ' ', alumnos.apellido) LIKE ?", ["%$word%"]);
                break;

            case 'profesor':
                $prof_ids = Profesor::select('id')
                    ->whereRaw("CONCAT(nombre, ' ', apellido) LIKE ?", ["%$word%"])
                    ->pluck('id');
                $query->whereIn('mesas.prof_presidente', $prof_ids);
                break;

            case 'carrera':
                $query->where('carreras.nombre', 'LIKE', "%$word%");
                break;

            case 'asignatura':
                $asig_ids = Asignatura::where('nombre', 'LIKE', "%$word%")->pluck('id');
                $query->whereIn('mesas.id_asignatura', $asig_ids);
                break;
        }
    }

    // Devolver resultados con paginación
    return $query
        ->select('mesas.*') // evita ambigüedades por los joins
        ->orderBy('mesas.fecha', 'desc')
        ->orderBy('mesas.llamado', 'asc')
        ->paginate($this->config['filas_por_tabla']);
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
