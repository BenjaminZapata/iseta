<?php

namespace App\Repositories\Admin;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Configuracion;

class AlumnoRepository
{
    public $config;

    public $availableFiels = ['nombre', 'dni', 'apellido'];

    public function __construct()
    {
        $this->config = Configuracion::todas();
    }

    public function index($request)
    {
        $query = Alumno::select('alumnos.*')
            ->leftJoin('egresadoinscripto', 'egresadoinscripto.id_alumno', '=', 'alumnos.id')
            ->leftJoin('carreras', 'carreras.id', '=', 'egresadoinscripto.id_carrera');

        // // Filtro por carrera
        // if ($request->filled('filter_carrera_id') && $request->input('filter_carrera_id') != 0) {
        //     $query->where('egresadoinscripto.id_carrera', $request->input('filter_carrera_id'));
        // }

        // // Filtro por ciudad
        // if ($request->filled('filter_ciudad') && $request->input('filter_ciudad') != 0) {
        //     $query->where('alumnos.ciudad', $request->input('filter_ciudad'));
        // }

        // Búsqueda por campo + texto
        if ($request->filled('filter_search_box') && in_array($request->input('filter_field'), $this->availableFiels)) {
            $field = $request->input('filter_field');
            $search = trim($request->input('filter_search_box'));

            if ($field === 'alumno') {
                $words = preg_split('/\s+/', trim($search));

                $query->where(function ($q) use ($words) {
                    foreach ($words as $word) {
                        $q->where(function ($sub) use ($word) {
                            $sub->where('alumnos.nombre', 'LIKE', "%$word%")
                                ->orWhere('alumnos.apellido', 'LIKE', "%$word%");
                        });
                    }
                });
            }
            if ($field === 'titulo_secundario') {
                $query->where('alumnos.titulo_secundario', '=', $request->titulo_secundario);
            }
        }
        switch ($request->filter_vencido) {
            case '1':
                $query->where('alumnos.titulo_secundario', 0)
                    ->whereDate('alumnos.fecha_titulo_secundario', '<=', now()->subDays(60));
                break;
            case '0':
                $query->where('alumnos.titulo_secundario', 0)
                    ->whereDate('alumnos.fecha_titulo_secundario', '=>', now()->subDays(60));
                break;
            default:
                break;
        }

        // Orden y paginación
        $query->orderBy('apellido')->orderBy('nombre');

        $filasPorTabla = (int) ($this->config['filas_por_tabla'] ?? 15);
        if ($filasPorTabla <= 0) {
            $filasPorTabla = 15;
        }

        return $query->paginate($filasPorTabla);
    }

    // Agregar una institución secundaria a un alumno
    public function agregarInstitucionSecundaria(string $nombre): Alumno
    {
        return Alumno::create([
            'nombre_institucion_secundaria' => $nombre,
        ]);
    }

    // Actualizar una institución secundaria de un alumno
    public function actualizarInstitucionSecundaria(int $id, string $nuevoNombre): ?Alumno
    {
        $alumno = Alumno::query()->find($id);
        if (! $alumno) {
            return null;
        }

        $alumno->nombre_institucion_secundaria = $nuevoNombre;
        $alumno->save();

        return $alumno;
    }
}
