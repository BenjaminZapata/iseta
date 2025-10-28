<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Alumno;
use App\Models\Cursada;
use Illuminate\Support\Facades\DB;
use Flasher\Laravel\Facade\Flasher as FlasherFacade;

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
    public $asignaturasBloqueadas = [];

    private $mapCondicion = [
        'Libre'      => 0,
        'Regular'    => 1,
        'Itinerante' => 2,
        'Oyente'     => 3,
    ];

    public function render()
    {
        $alumnos = collect();

        if ($this->nombre || $this->apellido || $this->dni) {
            if (!($this->dni || ($this->nombre && $this->apellido))) {
                $this->erroresValidacion[] = "Debe ingresar al menos DNI o nombre y apellido.";
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
            'alumnos' => $alumnos,
            'mostrarBoton' => $this->mostrarBoton
        ]);
    }

    public function seleccionarAlumno($id)
    {
        $this->alumnoSeleccionado = Alumno::find($id);
        $this->carreraSeleccionada = null;
        $this->materiasCarrera = [];
        $this->asignaturasSeleccionadas = [];
        $this->condiciones = []; // 🔹 Reinicia condiciones
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

            // 🔹 Ahora las condiciones empiezan vacías
            foreach ($this->materiasCarrera as $asig) {
                $this->condiciones[$asig->id] = ''; 
            }

            $this->calcularAsignaturasBloqueadas();
        }
    }

    private function calcularAsignaturasBloqueadas()
    {
        if (!$this->alumnoSeleccionado) return;

        $asignaturasCursadas = Cursada::where('id_alumno', $this->alumnoSeleccionado->id)
            ->pluck('id_asignatura')
            ->toArray();

        $this->asignaturasBloqueadas = [];

        $correlativas = DB::table('correlatividades')
            ->whereIn('id_asignatura', $this->materiasCarrera->pluck('id'))
            ->get()
            ->groupBy('id_asignatura');

        $mapAsignaturaNombre = $this->materiasCarrera->pluck('nombre', 'id')->toArray();

        foreach ($this->materiasCarrera as $asig) {
            $faltantes = [];

            foreach ($correlativas[$asig->id] ?? [] as $c) {
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
        $errores = [];

        if (!$this->alumnoSeleccionado) $errores[] = 'Debe seleccionar un alumno.';
        if (!$this->carreraSeleccionada) $errores[] = 'Debe seleccionar una carrera.';
        if (count($this->asignaturasSeleccionadas) === 0) $errores[] = 'Debe seleccionar al menos una asignatura.';

        $mapAsignaturaNombre = $this->materiasCarrera->pluck('nombre', 'id')->toArray();

        foreach ($this->asignaturasSeleccionadas as $idAsignatura) {
            $nombreAsignatura = $mapAsignaturaNombre[$idAsignatura] ?? "ID {$idAsignatura}";

            // ⚠️ Validación de condición vacía
            if (!isset($this->condiciones[$idAsignatura]) || $this->condiciones[$idAsignatura] === '') {
                $errores[] = "Debe elegir una condición para {$nombreAsignatura}.";
            }

            if (isset($this->asignaturasBloqueadas[$idAsignatura])) {
                $errores[] = "No se puede registrar {$nombreAsignatura} porque faltan las correlativas: {$this->asignaturasBloqueadas[$idAsignatura]}";
            }

            $existe = Cursada::where('id_alumno', $this->alumnoSeleccionado->id)
                ->where('id_asignatura', $idAsignatura)
                ->where('id_carrera', $this->carreraSeleccionada)
                ->where('anio_cursada', now()->year)
                ->exists();

            if ($existe) {
                $errores[] = "La cursada de {$nombreAsignatura} ya existe para este alumno este año.";
            }
        }

        // Si hay errores, mostramos con Flasher
        if (!empty($errores)) {
            foreach ($errores as $msg) {
                FlasherFacade::addError($msg);
            }
            return;
        }

        // Guardar cursadas
        foreach ($this->asignaturasSeleccionadas as $idAsignatura) {
            Cursada::create([
                'anio_cursada' => now()->year,
                'aprobada' => 3,
                'id_alumno' => $this->alumnoSeleccionado->id,
                'id_asignatura' => $idAsignatura,
                'id_carrera' => $this->carreraSeleccionada,
                'condicion' => $this->mapCondicion[array_search((int)$this->condiciones[$idAsignatura], $this->mapCondicion) ?? 'Regular'] ?? 1,
            ]);
        }

        FlasherFacade::addSuccess('Cursadas registradas correctamente.');

        $this->asignaturasSeleccionadas = [];
        $this->condiciones = [];
        $this->calcularAsignaturasBloqueadas();
    }
}
