<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Alumno;
use App\Models\Cursada;
use Illuminate\Support\Facades\DB;

class RegistrarCursada extends Component
{
    public $nombre = '';
    public $apellido = '';
    public $dni = '';

    public $alumnoSeleccionado = null;
    public $carreraSeleccionada = null;
    public $materiasCarrera = [];
    public $asignaturasSeleccionadas = [];
    public $condiciones = [];
    public $erroresValidacion = [];

    public $mostrarBoton = false;
    public $mensaje = null;

    private $mapCondicion = [
        'Libre'      => 0,
        'Regular'    => 1,
        'Promocion'  => 2,
        'Equivalencia'=>3,
        'Desertor'   => 4,
        'Itinerante' => 5,
        'Oyente'     => 6,
    ];

    public function render()
    {
        $alumnos = [];

        if ($this->nombre || $this->apellido || $this->dni) {
            $alumnos = Alumno::query()
                ->when($this->nombre, fn($q) => $q->where('nombre', 'like', "%{$this->nombre}%"))
                ->when($this->apellido, fn($q) => $q->where('apellido', 'like', "%{$this->apellido}%"))
                ->when($this->dni, fn($q) => $q->where('dni', 'like', "%{$this->dni}%"))
                ->take(10)
                ->get();
        }

        return view('livewire.registrar-cursada', [
            'alumnos' => $alumnos,
        ]);
    }

    public function seleccionarAlumno($id)
    {
        $this->alumnoSeleccionado = Alumno::find($id);
        $this->carreraSeleccionada = null;
        $this->materiasCarrera = [];
        $this->asignaturasSeleccionadas = [];
        $this->condiciones = [];
        $this->mostrarBoton = false;
        $this->mensaje = null;
        $this->erroresValidacion = [];
    }

    public function activarBoton()
    {
        $this->mostrarBoton = !is_null($this->carreraSeleccionada);
    }

    public function verMaterias()
    {
        if ($this->carreraSeleccionada) {
            $this->materiasCarrera = DB::table('carrera_asignatura_profesor')
                ->join('asignaturas', 'carrera_asignatura_profesor.id_asignatura', '=', 'asignaturas.id')
                ->where('carrera_asignatura_profesor.id_carrera', $this->carreraSeleccionada)
                ->select('asignaturas.id as id', 'asignaturas.nombre')
                ->distinct()
                ->get();

            foreach ($this->materiasCarrera as $asig) {
                if (!isset($this->condiciones[$asig->id])) {
                    $this->condiciones[$asig->id] = 'Regular'; // default
                }
            }
        }
    }

    public function guardarCursada()
    {
        $this->erroresValidacion = [];
        $this->mensaje = null;

        if (!$this->alumnoSeleccionado) {
            $this->erroresValidacion[] = 'Debe seleccionar un alumno.';
            return;
        }

        if (!$this->carreraSeleccionada) {
            $this->erroresValidacion[] = 'Debe seleccionar una carrera.';
            return;
        }

        if (count($this->asignaturasSeleccionadas) === 0) {
            $this->erroresValidacion[] = 'Debe seleccionar al menos una asignatura.';
            return;
        }

        foreach ($this->asignaturasSeleccionadas as $idAsignatura) {
            $condicionStr = $this->condiciones[$idAsignatura] ?? null;

            if (!$condicionStr || !isset($this->mapCondicion[$condicionStr])) {
                $this->erroresValidacion[] = "Debe elegir una condición válida para la asignatura ID {$idAsignatura}.";
                continue;
            }

            $condicion = $this->mapCondicion[$condicionStr];

            // Evitar duplicados
            $existe = Cursada::where('id_alumno', $this->alumnoSeleccionado->id)
                ->where('id_asignatura', $idAsignatura)
                ->where('id_carrera', $this->carreraSeleccionada)
                ->where('anio_cursada', now()->year)
                ->exists();

            if ($existe) {
                $this->erroresValidacion[] = "La cursada de la asignatura ID {$idAsignatura} ya existe para este alumno.";
                continue;
            }

            Cursada::create([
                'anio_cursada' => now()->year,
                'aprobada' => 0,
                'id_alumno' => $this->alumnoSeleccionado->id,
                'id_asignatura' => $idAsignatura,
                'id_carrera' => $this->carreraSeleccionada,
                'condicion' => $condicion,
            ]);
        }

        if (empty($this->erroresValidacion)) {
            $this->mensaje = 'Cursadas registradas correctamente.';
            $this->asignaturasSeleccionadas = [];
            $this->condiciones = [];
        }
    }
}
