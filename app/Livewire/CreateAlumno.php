<?php

namespace App\Livewire;

use App\Livewire\Forms\AlumnoForm;
use Livewire\Component;
use App\Models\Alumno;
use App\Models\Carrera;

class CreateAlumno extends Component
{
    // Paso 1: datos alumno
    public AlumnoForm $form;
    public $alumno;

    // Paso 2: carreras + inscripción
    public $todasCarreras = [];
    public $carrerasSeleccionadas = [];

    public ?Carrera $carrera = null; // carrera en edición de inscripción

    public $anio_inscripcion;
    public $indice_libro_matriz;
    public $anio_finalizacion;
    public $estado;
    public $show = false; // controla visibilidad del form de inscripción

    // Control de pasos
    public $step = 1;

    public function mount()
    {
        $this->todasCarreras = Carrera::all();
    }

    /* ----------- Paso 1 ----------- */
    public function siguientePaso()
    {
        if ($this->step == 1) {
            $data = $this->form->validateAlumnos();
            $this->alumno = new Alumno($data);
        }
        $this->step++;
    }

    public function pasoAnterior()
    {
        $this->step--;
    }

    /* ----------- Paso 2: carreras ----------- */
    public function agregarInscripcion($carreraId)
    {
        $this->carrera = Carrera::find($carreraId);
        if (!$this->carrera) return;

        // reset datos previos de inscripción
        $this->reset(['anio_inscripcion', 'indice_libro_matriz', 'anio_finalizacion', 'estado']);
        $this->show = true;
    }

    public function eliminarCarrera($carreraId)
    {
        $this->carrerasSeleccionadas = array_filter(
            $this->carrerasSeleccionadas,
            fn($c) => $c['id_carrera'] != $carreraId
        );
    }

    public function guardarInscripcion()
    {
        $this->validate([
            'anio_inscripcion' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'indice_libro_matriz' => ['nullable', 'string', 'max:50'],
            'anio_finalizacion' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'estado' => ['required', 'integer'],
        ]);

        $this->carrerasSeleccionadas[] = [
            'id_carrera' => $this->carrera->id,
            'carrera_nombre' => $this->carrera->nombre,
            'anio_inscripcion' => $this->anio_inscripcion,
            'indice_libro_matriz' => $this->indice_libro_matriz,
            'anio_finalizacion' => $this->anio_finalizacion,
            'estado' => $this->estado,
        ];

        $this->show = false;
        $this->carrera = null;
    }

    /* ----------- Paso 3: guardar todo ----------- */
    public function guardarTodo()
    {
        $alumno = Alumno::create($this->form->all());

        foreach ($this->carrerasSeleccionadas as $c) {
            $alumno->inscripciones()->create([
                'carrera_id' => $c['id_carrera'],
                'anio_inscripcion' => $c['anio_inscripcion'],
                'indice_libro_matriz' => $c['indice_libro_matriz'],
                'anio_finalizacion' => $c['anio_finalizacion'],
                'estado' => $c['estado'],
            ]);
        }

        session()->flash('message', 'Alumno creado e inscrito correctamente.');

        $this->reset();
        $this->step = 1;
        $this->todasCarreras = Carrera::all();
    }

    public function render()
    {
        return view('livewire.create-alumno');
    }
}
