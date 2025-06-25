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
    $cursadas = Cursada::with(['alumno', 'asignatura'])
        ->leftJoin('asignaturas', 'asignaturas.id', '=', 'cursadas.id_asignatura')
        ->leftJoin('alumnos', 'alumnos.id', '=', 'cursadas.id_alumno')
        ->leftJoin('carreras', 'carreras.id', '=', 'cursadas.id_carrera')
        ->select('cursadas.*') // importante para evitar conflictos de columnas duplicadas
        ->when($request->filled('filter_carrera_id') && $request->input('filter_carrera_id') != 0, function ($query) use ($request) {
            $query->where('carreras.id', $request->input('filter_carrera_id'));
        })
        ->when($request->filled('filter_asignatura_id') && $request->input('filter_asignatura_id') != 0, function ($query) use ($request) {
            $query->where('cursadas.id_asignatura', $request->input('filter_asignatura_id'));
        })
        ->when($request->filled('filter_alumno_id') && $request->input('filter_alumno_id') != 0, function ($query) use ($request) {
            $query->where('alumnos.id', $request->input('filter_alumno_id'));
        })
        ->when($request->filled('filter_condicion') && $request->input('filter_condicion') != 0, function ($query) use ($request) {
            $query->where('cursadas.condicion', $request->input('filter_condicion'));
        })
        ->when($request->filled('filter_aprobada') && $request->input('filter_aprobada') != 0, function ($query) use ($request) {
            $query->where('cursadas.aprobada', $request->input('filter_aprobada'));
        })
        ->when(
            $request->filled('filter_search_box') &&
            in_array($request->input('filter_field'), $this->availableFiels),
            function ($query) use ($request) {
                $query->where($request->input('filter_field'), 'LIKE', '%' . $request->input('filter_search_box') . '%');
            }
        )
        ->orderBy('anio_cursada', 'DESC')
        ->paginate(25);

        return $cursadas;
    }
}
