<?php

namespace App\Repositories\Admin;

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
            ->with(['carrera', 'asignatura'])
            ->when($request->filled('filter_carrera_id') && $request->input('filter_carrera_id') != 0, function ($query) use ($request) {
                $query->where('id_carrera', $request->input('filter_carrera_id'));
            })
            ->when($request->filled('filter_alumno_id') && $request->input('filter_alumno_id') != 0, function ($query) use ($request) {
                $query->where('id_alumno', $request->input('filter_alumno_id'));
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

        // Creamos un array de grupos para usar en whereIn multi-column (Compoships)
        $groupsArray = $cursadasSummary->map(fn ($item): array => [
            'id_carrera' => $item->id_carrera,
            'anio_cursada' => $item->anio_cursada,
        ]
        )->toArray();

        // Traemos todas las cursadas de los grupos visibles en la página, con sus alumnos
        $allCursadas = Cursada::with('alumno')
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
            ->when($request->filled('filter_alumno_id') && $request->input('filter_alumno_id') != 0, function ($query) use ($request) {
                $query->where('id_alumno', $request->input('filter_alumno_id'));
            })
            ->when(! empty($groupsArray), function ($query) use ($groupsArray) {
                $query->whereIn(
                    ['id_carrera', 'anio_cursada'],
                    $groupsArray
                );
            })
            ->get()
            ->groupBy(['id_carrera', 'id_asignatura', 'anio_cursada']);

        // Retornamos un array con resumen y cursadas completas
        return [
            'summary' => $cursadasSummary,
            'allCursadas' => $allCursadas,
        ];
    }
}
