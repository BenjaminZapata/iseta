<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Alumno;
use App\Models\Cursada;
use App\Models\Correlativa;
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
    public $mensaje = null;
    public $mostrarBoton = false;

    // IDs de asignaturas bloqueadas por correlativas, con mensaje
    public $asignaturasBloqueadas = [];

    private $mapCondicion = [
        'Libre'      => 0,
        'Regular'    => 1,
        'Itinerante' => 2,
        'Oyente'     => 3,
    ];

    public function render()
    {
        $alumnos = [];

        if ($this->nombre || $this->apellido || $this->dni) {
            // Validar que al menos DNI o nombre+apellido estén completos
            if (!($this->dni || ($this->nombre && $this->apellido))) {
                $this->erroresValidacion[] = "Debe ingresar al menos DNI o nombre y apellido.";
                $alumnos = collect();
            } else {
                $alumnos = Alumno::query()
                    ->when($this->nombre, fn($q) => $q->where('nombre', 'like', "%{$this->nombre}%"))
                    ->when($this->apellido, fn($q) => $q->where('apellido', 'like', "%{$this->apellido}%"))
                    ->when($this->dni, fn($q) => $q->where('dni', 'like', "%{$this->dni}%"))
                    ->take(10)
                    ->get();
            }
        }

        return view('livewire.registrar-cursada', [
            'alumnos' => $alumnos
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
        $this->asignaturasBloqueadas = [];
    }

    public function activarBoton()
    {
        $this->mostrarBoton = !is_null($this->carreraSeleccionada);
    }

    public function verMaterias()
    {
        if ($this->carreraSeleccionada) {
            $this->materiasCarrera = \App\Models\Carrera::find($this->carreraSeleccionada)
                ?->asignaturas()
                ->withPivot('anio')
                ->orderBy('pivot_anio')
                ->get() ?? collect();

            foreach ($this->materiasCarrera as $asig) {
                if (!isset($this->condiciones[$asig->id])) {
                    $this->condiciones[$asig->id] = 'Regular'; // valor por defecto
                }
            }

            $this->calcularAsignaturasBloqueadas();
        }
    }

 private function calcularAsignaturasBloqueadas()
{
    // Traemos cursadas previas del alumno
    $asignaturasCursadas = Cursada::where('id_alumno', $this->alumnoSeleccionado->id)
        ->pluck('id_asignatura')
        ->toArray();

    $this->asignaturasBloqueadas = [];

    // Traemos correlativas de las asignaturas de la carrera
    $correlativas = DB::table('correlatividades')
        ->whereIn('id_asignatura', $this->materiasCarrera->pluck('id'))
        ->get()
        ->groupBy('id_asignatura'); // agrupamos por asignatura

    // Mapa de nombres de asignaturas
    $mapAsignaturaNombre = $this->materiasCarrera->pluck('nombre', 'id')->toArray();

    foreach ($this->materiasCarrera as $asig) {
        $faltantes = [];

        foreach ($correlativas[$asig->id] ?? [] as $c) {
            // Usamos el nombre real de la columna
            $idCorrelativa = $c->id_asignatura_correlativa; 

            if (!in_array($idCorrelativa, $asignaturasCursadas)) {
                $faltantes[] = $mapAsignaturaNombre[$idCorrelativa] ?? "ID {$idCorrelativa}";
            }
        }

        if (!empty($faltantes)) {
            $this->asignaturasBloqueadas[$asig->id] = implode(', ', $faltantes);
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

        $asignaturasCursadas = Cursada::where('id_alumno', $this->alumnoSeleccionado->id)
            ->pluck('id_asignatura')
            ->toArray();

        $mapAsignaturaNombre = $this->materiasCarrera->pluck('nombre', 'id')->toArray();

        $correlativas = DB::table('correlativas')
            ->whereIn('id_asignatura', $this->asignaturasSeleccionadas)
            ->get()
            ->groupBy('id_asignatura');

        foreach ($this->asignaturasSeleccionadas as $idAsignatura) {
            $nombreAsignatura = $mapAsignaturaNombre[$idAsignatura] ?? "ID {$idAsignatura}";
            $condicionStr = $this->condiciones[$idAsignatura] ?? null;

            if (!$condicionStr || !isset($this->mapCondicion[$condicionStr])) {
                $this->erroresValidacion[] = "Debe elegir una condición válida para la asignatura {$nombreAsignatura}.";
                continue;
            }

            if (isset($this->asignaturasBloqueadas[$idAsignatura])) {
                $this->erroresValidacion[] = "No se puede registrar {$nombreAsignatura} porque faltan las correlativas: {$this->asignaturasBloqueadas[$idAsignatura]}";
                continue;
            }

            $condicion = $this->mapCondicion[$condicionStr];

            $existe = Cursada::where('id_alumno', $this->alumnoSeleccionado->id)
                ->where('id_asignatura', $idAsignatura)
                ->where('id_carrera', $this->carreraSeleccionada)
                ->where('anio_cursada', now()->year)
                ->exists();

            if ($existe) {
                $this->erroresValidacion[] = "La cursada de {$nombreAsignatura} ya existe para este alumno este año.";
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

            $asignaturasCursadas[] = $idAsignatura;
        }

        if (empty($this->erroresValidacion)) {
            $this->mensaje = 'Cursadas registradas correctamente.';
            $this->asignaturasSeleccionadas = [];
            $this->condiciones = [];
            $this->calcularAsignaturasBloqueadas();
        }
    }
}
