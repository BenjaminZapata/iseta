<?php

namespace App\Repositories\Admin;

use App\Models\Carrera;

use App\Models\CarreraAsignatura;
use App\Models\CarreraAsignaturaProfesor;
use App\Models\Configuracion;
use PhpParser\Node\Expr\FuncCall;


class CarreraRepository
{

    public $config;
    public $availableFiels = ['nombre', 'asignatura','resolucion'];

    public function __construct()
    {
        $this->config = Configuracion::todas();
    }
public function index($request)
{
    $filterVigente = $request->filled('filter_vigente') ? $request->input('filter_vigente') : '1';

    $query = Carrera::with('asignaturas')
        ->when($filterVigente !== '', fn($q) => $q->where('vigente', (int) $filterVigente))
        ->when(
            $request->filled('filter_search_box') && in_array($request->input('filter_field'), ['nombre','resolucion','asignatura']),
            function ($query) use ($request) {
                $word = str_replace(' ', '%', $request->input('filter_search_box'));
                $field = $request->input('filter_field');

                if ($field === 'asignatura') {
                    $query->whereHas('asignaturas', fn($q) => $q->where('nombre', 'LIKE', "%$word%"));
                } else {
                    $query->where($field, 'LIKE', "%$word%");
                }
            }
        )
        ->orderByDesc('vigente')
        ->orderByDesc('anio_apertura')
        ->orderBy('nombre');

    $carreras = $query->paginate($this->config['filas_por_tabla']);
    
    return $carreras;
}



    public function setAsignatura($asignatura, $carrera)
    {
        // Implement logic to associate asignatura with carrera if needed
        // Example: return $carrera->asignaturas()->attach($asignatura->id);
    }

    public function GETresolucion($carrera)
    {
        return Carrera::where('id', $carrera->id)
            ->select('nombre', 'resolucion', 'vigente', 'resolucion_archivo')
            ->first();
    }

}
