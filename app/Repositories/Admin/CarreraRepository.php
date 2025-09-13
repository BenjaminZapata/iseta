<?php

namespace App\Repositories\Admin;

use App\Models\Carrera;

use App\Models\CarreraAsignatura;
use App\Models\CarreraAsignaturaProfesor;
use App\Models\Configuracion;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Log;


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
    $filterVigente = $request->input('filter_vigente', null); // '', '0', '1'
    $hasSearch     = $request->filled('filter_search_box') && $request->filled('filter_field');
    $query         = Carrera::with('asignaturas');

    if ($hasSearch) {
        $word  = trim($request->input('filter_search_box'));
        $field = $request->input('filter_field');

        switch ($field) {
            case 'asignatura':
                $query->whereHas('asignaturas', function ($q) use ($word) {
                    $tokens = array_filter(array_map('trim', preg_split('/[^\p{L}\p{N}]+/u', $word)));
                    foreach ($tokens as $t) {
                        if (mb_strlen($t) < 2) continue;
                        $q->whereRaw(
                            "asignaturas.nombre COLLATE utf8mb4_unicode_ci LIKE ?",
                            ["%{$t}%"]
                        );
                    }
                });
                break;

            case 'resolucion':
                $query->whereRaw(
                    "CAST(carreras.resolucion AS CHAR) COLLATE utf8mb4_unicode_ci LIKE ?",
                    ["%{$word}%"]
                );
                break;

            case 'nombre':
            default:
                $tokens = array_filter(array_map('trim', preg_split('/[^\p{L}\p{N}]+/u', $word)));
                $query->where(function ($sub) use ($tokens) {
                    foreach ($tokens as $t) {
                        if (mb_strlen($t) < 2) continue;
                        $sub->whereRaw(
                            "carreras.nombre COLLATE utf8mb4_unicode_ci LIKE ?",
                            ["%{$t}%"]
                        );
                    }
                });
                break;
        }

    } else {
        // 🔹 Caso sin búsqueda
        if ($filterVigente === '0' || $filterVigente === '1') {
            $query->where('carreras.vigente', (int)$filterVigente);
        } else {
            // Sin filtro y sin búsqueda → mostrar solo vigentes
            $query->where('carreras.vigente', 1);
        }
    }

    $query->orderByDesc('carreras.vigente')
          ->orderByDesc('carreras.anio_apertura')
          ->orderBy('carreras.nombre');

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
