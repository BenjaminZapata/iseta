<?php

namespace App\Repositories;

use App\Models\Carrera;
use App\Models\CarreraDefault;
use App\Models\Configuracion;
use App\Models\Cursada;
use App\Models\Examen;
use Illuminate\Support\Facades\Auth;
use Log;

class AlumnoDataRepository
{
    public $config;

    public function __construct()
    {
        $this->config = Configuracion::todas();
    }

    public function examenes($filtro, $campo, $orden)
    {
        $defaultCarreraId = Carrera::getDefault(Auth::user())?->id;

        return Examen::join('asignaturas', 'asignaturas.id', 'examenes.id_asignatura')
            ->join('carrera_asignatura_profesor', 'carrera_asignatura_profesor.id_asignatura', '=', 'asignaturas.id')
            ->where('carrera_asignatura_profesor.id_carrera', $defaultCarreraId)
            ->where('examenes.id_alumno', Auth::id())
            ->when($filtro, fn ($query) => $query->where('asignaturas.nombre', 'LIKE', "%$filtro%"))
            ->when($campo == 'aprobadas', fn ($query) => $query->where('examenes.nota', '>=', 4))
            ->when($campo == 'desaprobadas', fn ($query) => $query->where('examenes.nota', '<', 4))
            ->orderBy('asignaturas.id')
            ->orderBy('examenes.fecha', 'desc')
            ->orderBy('examenes.nota', 'asc')
            ->get();
    }

    public function cursadas($filtro, $campo, $orden)
    {
        $query = Cursada::query()
            ->where('id_alumno', '=', Auth::id())
            ->join('asignaturas', 'cursadas.id_asignatura', 'asignaturas.id')
            // si tiene un filtro en el campo de texto
            ->when($filtro, fn ($query, $filtro) => $query->where('asignaturas.nombre', 'LIKE', '%'.$filtro.'%'))

            // Si se filtra por aprobadas
            ->when($campo == 'aprobadas', function ($query) {
                $query->where(function ($sub) {
                    $sub->where('aprobada', 1)
                        ->orWhereIn('condicion', [0, 2, 3]);
                });
            })

            // Si se filtra por desaprobadas
            ->when($campo == 'desaprobadas', function ($query) {
                $query->where('aprobada', 2)
                    ->whereNotIn('condicion', [0, 2, 3]);
            })
            ->with('asignatura')

            // Ordenamiento
            ->when($orden == 'anio_cursada', fn ($query) => $query->orderBy('cursadas.anio_cursada'))
            ->when($orden == 'anio_cursada_desc', fn ($query) => $query->orderBy('cursadas.anio_cursada', 'desc'))
            ->orderBy('asignaturas.id');

        Log::debug($query->get());

        return $query->get();

    }

    public function setCarreraDefault($alumno, $carrera)
    {
        $data = [
            'id_alumno' => $alumno,
            'id_carrera' => $carrera,
        ];

        CarreraDefault::updateOrInsert(['id_alumno' => $alumno], $data);
    }
}
