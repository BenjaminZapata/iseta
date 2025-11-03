<?php

namespace App\Repositories\Admin;

use App\Models\Configuracion;
use App\Models\Cursada;

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
        $cursadasSummaryQuery = Cursada::select('id_carrera', 'id_asignatura', 'anio_cursada')
            ->with(['carrera', 'asignatura'])
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
            ->orderBy('anio_cursada', 'DESC');

        // Paginamos las filas resumen
        $cursadasSummary = $cursadasSummaryQuery->paginate($this->config['filas_por_tabla']);

        // Creamos un array de grupos para usar en whereIn multi-column (Compoships)
        $groupsArray = $cursadasSummary->map(fn ($item): array => [
            'id_carrera' => $item->id_carrera,
            'id_asignatura' => $item->id_asignatura,
            'anio_cursada' => $item->anio_cursada,
        ]
        )->toArray();

        // Traemos todas las cursadas de los grupos visibles en la página, con sus alumnos
        $allCursadas = Cursada::with('alumno')
            ->whereIn(['id_carrera', 'id_asignatura', 'anio_cursada'], $groupsArray)
            ->get()
            ->groupBy(['id_carrera', 'id_asignatura', 'anio_cursada']);

        // Retornamos un array con resumen y cursadas completas
        return [
            'summary' => $cursadasSummary,
            'allCursadas' => $allCursadas,
        ];
    }
}
