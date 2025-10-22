<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Cursada;
use App\Models\Egresado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegistrarCursada extends Component
{
    // --- FILTROS DE BÚSQUEDA ---
    #[Url(except: '')]
    public $nombre = '';

    #[Url(except: '')]
    public $apellido = '';

    #[Url(except: '')]
    public $dni = '';

    // --- SELECCIÓN ---
    public $alumnoSeleccionado = null;
    public $carreraSeleccionada = null;
    public $materiasCarrera = [];
    public $asignaturasSeleccionadas = [];
    public $condiciones = [];
    public $erroresValidacion = [];

    public function render()
    {
        $alumnos = [];

        // Solo busca si hay filtros válidos (nombre+apellido o DNI)
        if (($this->apellido && $this->nombre) || $this->dni) {
            $alumnos = Alumno::query()
                ->when($this->nombre, fn($q) => $q->where('nombre', 'like', '%' . $this->nombre . '%'))
                ->when($this->apellido, fn($q) => $q->where('apellido', 'like', '%' . $this->apellido . '%'))
                ->when($this->dni, fn($q) => $q->where('dni', 'like', '%' . $this->dni . '%'))
                ->take(10)
                ->get();
        }

        // Obtener carreras del alumno seleccionado
        $carreras = [];
        if ($this->alumnoSeleccionado) {
            $carreras = Egresado::where('id_alumno', $this->alumnoSeleccionado->id)
                ->with('carrera')
                ->get()
                ->pluck('carrera')
                ->filter()
                ->values();
        }

        return view('livewire.registrar-cursada', [
            'alumnos' => $alumnos,
            'carreras' => $carreras,
        ]);
    }

    public function seleccionarAlumno($id)
    {
        $this->alumnoSeleccionado = Alumno::find($id);
        $this->reset([
            'nombre', 'apellido', 'dni',
            'carreraSeleccionada', 'materiasCarrera',
            'asignaturasSeleccionadas', 'condiciones'
        ]);
    }

public function cargarMaterias()
{
    if ($this->carreraSeleccionada) {
        $this->materiasCarrera = collect(
            DB::table('carrera_asignatura_profesor')
                ->join('asignaturas', 'carrera_asignatura_profesor.id_asignatura', '=', 'asignaturas.id')
                ->where('carrera_asignatura_profesor.id_carrera', $this->carreraSeleccionada)
                ->select('asignaturas.id as id', 'asignaturas.nombre')
                ->distinct()
                ->get()
        );
    }
}


    public function registrar()
    {
        $this->erroresValidacion = [];

        if (!$this->alumnoSeleccionado || !$this->carreraSeleccionada || empty($this->asignaturasSeleccionadas)) {
            $this->erroresValidacion[] = 'Debe seleccionar alumno, carrera y al menos una asignatura.';
            return;
        }

        foreach ($this->asignaturasSeleccionadas as $asignaturaId) {
            $condicion = $this->condiciones[$asignaturaId] ?? null;
            if (is_null($condicion)) {
                $this->erroresValidacion[] = "Debe seleccionar la condición para la asignatura ID $asignaturaId.";
                continue;
            }

            // Validar correlatividades
            if (!$this->cumpleCorrelativas($this->alumnoSeleccionado->id, $asignaturaId)) {
                $this->erroresValidacion[] = "El alumno no cumple correlatividades para la asignatura ID $asignaturaId.";
                continue;
            }

            // Validar plazos de inscripción
            if (!$this->dentroDePlazo()) {
                $this->erroresValidacion[] = "El plazo de inscripción está vencido.";
                continue;
            }

            // Registrar cursada
            Cursada::create([
                'alumno_id' => $this->alumnoSeleccionado->id,
                'carrera_id' => $this->carreraSeleccionada,
                'asignatura_id' => $asignaturaId,
                'condicion' => $condicion,
            ]);
        }

        if (empty($this->erroresValidacion)) {
            session()->flash('success', 'Inscripción realizada con éxito.');
            $this->reset(['materiasCarrera', 'asignaturasSeleccionadas', 'condiciones']);
        }
    }

    private function cumpleCorrelativas($alumnoId, $asignaturaId)
    {
        // Implementar tu validación real de correlatividades aquí
        return true;
    }

    private function dentroDePlazo()
    {
        // Implementar validación de fechas aquí
        return true;
    }
}
