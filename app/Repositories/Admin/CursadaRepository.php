<?php

namespace App\Repositories\Admin;

use App\Models\Asignatura;
use App\Models\Configuracion;
use App\Models\Cursada;
use DB;

class CursadaRepository
{
    public $config;

    public $availableFiels = ['anio_cursada'];

    public function __construct()
    {
        $this->config = Configuracion::todas();
    }

    public function index($request)
    {
        // 1️⃣ Traemos solo las filas resumen (una por grupo) con filtros aplicados
        $cursadasSummaryQuery = Cursada::select('id_carrera', DB::raw('ANY_VALUE(id_asignatura) as id_asignatura'), 'anio_cursada', DB::raw('ANY_VALUE(aprobada) as aprobada'), DB::raw('ANY_VALUE(condicion) as condicion'))
            ->with('carrera')
            ->when($request->filled('filter_carrera_id') && $request->input('filter_carrera_id') != 0, function ($query) use ($request) {
                $query->where('id_carrera', $request->input('filter_carrera_id'));
            })
            ->when($request->filled('filter_asignatura_id') && $request->input('filter_asignatura_id') != 0, function ($query) use ($request) {
                $query->where('id_asignatura', $request->input('filter_asignatura_id'));
            })
            ->when($request->filled('filter_condicion') && $request->input('filter_condicion') != 0, function ($query) use ($request) {
                $query->where('condicion', $request->input('filter_condicion'));
            })
            ->when($request->filled('filter_aprobada') && $request->input('filter_aprobada') != 0, function ($query) use ($request) {
                $query->where('aprobada', $request->input('filter_aprobada'));
            })
            ->distinct()
            ->orderBy('anio_cursada', 'DESC')
            ->groupBy('id_carrera', 'anio_cursada');

        // Paginamos las filas resumen
        $cursadasSummary = $cursadasSummaryQuery->paginate($this->config['filas_por_tabla'] / 2);

        // Retornamos un array con resumen y cursadas completas
        return $cursadasSummary;

    }

    public static function asignaturaCarreraAnio($grupo)
    {
        $value = Asignatura::query()
            ->whereHas('cursadas', function ($query) use ($grupo) {
                $query->where('id_carrera', $grupo->id_carrera)
                    ->where('anio_cursada', $grupo->anio_cursada);
            })
            ->distinct()
            ->lazy(30);

        return $value;
    }

    public static function cursadasAsignatura($id_carrera, $anio_cursada, $asignatura)
    {
        $value = Cursada::with('alumno')
            ->where('id_carrera', $id_carrera)
            ->where('anio_cursada', $anio_cursada)
            ->where('id_asignatura', $asignatura->id)
            ->lazy(30); // LazyCollection de alumnos/cursadas

        return $value;
    }
}
