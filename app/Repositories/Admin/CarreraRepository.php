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
    public $availableFiels = ['nombre', 'asignatura'];

    public function __construct()
    {
        $this->config = Configuracion::todas();
    }

    public function index($request)
    {
        // Usamos valor por defecto si el filtro no viene del usuario
        $filterVigente = $request->filled('filter_vigente')
            ? $request->input('filter_vigente')
            : '1'; // mostrar solo vigentes por defecto

        return Carrera::query()
            ->with('asignaturas')
            ->when(
                $filterVigente !== '',
                function ($query) use ($filterVigente) {
                    $query->where('vigente', (int) $filterVigente);
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
            ->orderByDesc('vigente')
            ->orderByDesc('anio_apertura')
            ->orderBy('nombre')
            ->paginate($this->config['filas_por_tabla']);
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
