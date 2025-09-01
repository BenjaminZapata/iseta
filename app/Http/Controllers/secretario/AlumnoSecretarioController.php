<?php

namespace App\Http\Controllers\Secretario;

use App\Http\Controllers\BaseController;
use App\Models\Alumno;
use App\Models\Cursada;
use App\Models\Examen;
use App\Repositories\Admin\AlumnoRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumnoSecretarioController extends BaseController
{
    protected $alumnosRepo;

    public $defaultFilters = [
        'filter_carrera_id' => 0,
        'filter_ciudad' => 0,
        'filter_estado_civil' => 0
    ];

    public function __construct(AlumnoRepository $alumnosRepo)
    {
        parent::__construct();
        $this->alumnosRepo = $alumnosRepo;
    }

    /**
     * Listado de alumnos (solo consulta, con filtros).
     */
    public function index(Request $request): View
    {
        $this->setFilters($request);
        $this->data['alumnos'] = $this->alumnosRepo->index($request);

        return view('Secretario.Alumnos.index', $this->data);
    }

    /**
     * Ver datos completos de un alumno (solo lectura).
     */
    public function show(Alumno $alumno): View
    {
        $cursadas = Cursada::select(
            'asignaturas.nombre as asignatura',
            'cursadas.aprobada',
            'cursadas.condicion',
            'cursadas.anio_cursada',
            'cursadas.id',
            'carreras.nombre as carrera',
            'asignaturas.anio as anio_asig'
        )
            ->join('asignaturas', 'cursadas.id_asignatura', '=', 'asignaturas.id')
            ->join('carrera_asignatura_profesor as cap', 'asignaturas.id', '=', 'cap.id_asignatura')
            ->join('carreras', 'cap.id_carrera', '=', 'carreras.id')
            ->where('cursadas.id_alumno', $alumno->id)
            ->orderBy('carreras.id')
            ->orderBy('asignaturas.anio')
            ->orderBy('asignaturas.id')
            ->orderBy('cursadas.anio_cursada')
            ->get();

        $examenes = Examen::select(
            'examenes.fecha',
            'asignaturas.nombre as asignatura',
            'examenes.nota',
            'examenes.id',
            'carreras.nombre as carrera',
            'asignaturas.anio as anio_asig'
        )
            ->join('asignaturas', 'examenes.id_asignatura', '=', 'asignaturas.id')
            ->join('carrera_asignatura_profesor as cap', 'asignaturas.id', '=', 'cap.id_asignatura')
            ->join('carreras', 'cap.id_carrera', '=', 'carreras.id')
            ->where('examenes.id_alumno', $alumno->id)
            ->orderBy('carreras.id')
            ->orderBy('asignaturas.anio')
            ->orderBy('examenes.fecha', 'desc')
            ->get();

        return view('Secretario.Alumnos.show', [
            'alumno' => $alumno,
            'cursadas' => $cursadas,
            'examenes' => $examenes,
            'carreras' => $alumno->carrerasIncriptas(),
        ]);
    }
}
