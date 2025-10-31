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
        // 1️⃣ Traemos solo las filas resumen (una por carrera + año)
        $cursadasSummaryQuery = Cursada::select('id_carrera', 'anio_cursada')
            ->with(['carrera'])
            ->when($request->filled('filter_carrera_id') && $request->input('filter_carrera_id') != 0, function ($query) use ($request) {
                $query->where('id_carrera', $request->input('filter_carrera_id'));
            })
            ->when($request->filled('filter_condicion') && $request->input('filter_condicion') != 0, function ($query) use ($request) {
                $query->where('condicion', $request->input('filter_condicion'));
            })
            ->when($request->filled('filter_aprobada') && $request->input('filter_aprobada') != 0, function ($query) use ($request) {
                $query->where('aprobada', $request->input('filter_aprobada'));
            })
            ->distinct()
            ->orderBy('anio_cursada', 'DESC');

        // 2️⃣ Paginamos los grupos (una fila por carrera + año)
        $cursadasSummary = $cursadasSummaryQuery->paginate($this->config['filas_por_tabla']);

        // 3️⃣ Creamos un array de grupos para hacer un whereIn compuesto (Compoships)
        $groupsArray = $cursadasSummary->map(fn ($item) => [
            'id_carrera' => $item->id_carrera,
            'anio_cursada' => $item->anio_cursada,
        ])->toArray();

        // 4️⃣ Traemos todas las cursadas de esos grupos visibles, con sus asignaturas y alumnos
        $allCursadas = Cursada::with(['asignatura', 'alumno'])
            ->whereIn(['id_carrera', 'anio_cursada'], $groupsArray)
            ->get()
            ->groupBy(['id_carrera', 'anio_cursada', 'id_asignatura']);

        // 5️⃣ Retornamos la estructura lista para la vista
        return [
            'summary' => $cursadasSummary,
            'allCursadas' => $allCursadas,
        ];
    }
}
