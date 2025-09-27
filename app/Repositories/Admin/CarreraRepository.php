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
    public $availableFiels = ['nombre', 'asignatura', 'resolucion'];

    public function __construct()
    {
        $this->config = Configuracion::todas();
    }
    public function index($request)
{
   $filterVigente = $request->input('filter_vigente', '');
    $resolucionNumero      = $request->input('filter_resolucion_numero');
    $resolucionAnio        = $request->input('filter_resolucion_anio');
    $hasSearch             = $request->filled('filter_search_box') && $request->filled('filter_field');
    $query                 = Carrera::with('asignaturas');

    // 🔍 Filtro de búsqueda general
    if ($hasSearch) {
        $word  = trim($request->input('filter_search_box'));
        $field = $request->input('filter_field');

        switch ($field) {
            case 'resolucion':
                $query->whereRaw("CAST(carreras.resolucion AS CHAR) COLLATE utf8mb4_unicode_ci LIKE ?", ["%{$word}%"]);
                break;

            case 'nombre':
            default:
                $tokens = array_filter(array_map('trim', preg_split('/[^\p{L}\p{N}]+/u', $word)));
                $query->where(function ($sub) use ($tokens) {
                    foreach ($tokens as $t) {
                        if (mb_strlen($t) < 2) continue;
                        $sub->whereRaw("carreras.nombre COLLATE utf8mb4_unicode_ci LIKE ?", ["%{$t}%"]);
                    }
                });
                break;
        }
    }

    
   if ($filterVigente === '0' || $filterVigente === '1') {
    $query->where('carreras.vigente', (int)$filterVigente);
} elseif ($filterVigente === '') {
    $query->where('carreras.vigente', 1); // Por defecto, solo vigentes
}

    if (!empty($resolucionNumero)) {
        $query->whereRaw("SUBSTRING_INDEX(carreras.resolucion, '/', 1) LIKE ?", ["%{$resolucionNumero}%"]);
    }

    if (!empty($resolucionAnio)) {
        $query->whereRaw("SUBSTRING_INDEX(carreras.resolucion, '/', -1) LIKE ?", ["%{$resolucionAnio}%"]);
    }

    
    $query->orderByDesc('carreras.vigente')
          ->orderByDesc('carreras.anio_apertura')
          ->orderBy('carreras.nombre');

    return $query->paginate($this->config['filas_por_tabla']);
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
