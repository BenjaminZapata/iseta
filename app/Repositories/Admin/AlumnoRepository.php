<?php

namespace App\Repositories\Admin;

use App\Models\Alumno;
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

        // Búsqueda por campo + texto
        if ($request->filled('filter_search_box') && in_array($request->input('filter_field'), $this->availableFiels)) {
            $field = $request->input('filter_field');
            $search = trim($request->input('filter_search_box'));
            switch ($field) {
                case 'dni':
                    $query->where('alumnos.dni', 'LIKE', "%$search%");
                    break;
                case 'apellido':
                    $query->where('alumnos.apellido', 'LIKE', "%$search%");
                    break;
                case 'nombre':
                    $query->where('alumnos.nombre', 'LIKE', "%$search%");
                    break;
                default:
                    break;
            }

        }
        if ($request->filled('filter_titulo')) {
            $query->where('alumnos.titulo_secundario', '=', $request->filter_titulo);
        }
        if ($request->filled('filter_vencido')) {
            $query->where('alumnos.titulo_secundario', 0)
                ->whereDate('alumnos.fecha_titulo_secundario', '<=', now()->subDays(60));
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
