<?php

namespace App\Repositories\Admin;

use App\Models\Carrera;

use App\Models\CarreraAsignatura;
use App\Models\CarreraAsignaturaProfesor;
use App\Models\Configuracion;
use PhpParser\Node\Expr\FuncCall;


class CarreraRepository{

    public $config;
    public $availableFiels = ['nombre','asignatura'];

    public function __construct() {
        $this->config = Configuracion::todas();
    }

    public function index($request){
        return Carrera::query()
            ->with('asignaturas') // eager loading
            ->when(
                $request->filled('filter_vigente') && $request->input('filter_vigente') != 0,
                function ($query) use ($request) {
                    $query->where('vigente', $request->input('filter_vigente') - 1);
                }
            )
            ->when(
                $request->filled('filter_search_box') &&
                in_array($request->input('filter_field'), $this->availableFiels),
                function ($query) use ($request) {
                    $word = str_replace(' ', '%', $request->input('filter_search_box'));
                    if ($request->input('filter_field') === 'asignatura') {
                        $query->whereHas('asignaturas', function ($q) use ($word) {
                            $q->where('nombre', 'LIKE', '%' . $word . '%');
                        });
                    } else {
                        $query->where($request->input('filter_field'), 'LIKE', '%' . $word . '%');
                    }
                }
            )
            ->orderBy('nombre')
            ->paginate($this->config['filas_por_tabla']);

    }

    public function setAsignatura($asignatura, $carrera){
        // Implement logic to associate asignatura with carrera if needed
        // Example: return $carrera->asignaturas()->attach($asignatura->id);
    }

    public function GETresolucion($carrera)
    {
        return Carrera::where('id', $carrera->id)
            ->select('nombre','resolucion', 'vigente', 'resolucion_archivo')
            ->first();
    }

}
