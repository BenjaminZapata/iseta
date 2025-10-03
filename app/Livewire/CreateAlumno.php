<?php

namespace App\Livewire;

use App\Livewire\Forms\AlumnoForm;
use App\Livewire\Forms\InscripcionForm;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Egresado;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateAlumno extends Component
{
    // Paso 1: datos alumno
    public AlumnoForm $form;

    public InscripcionForm $iForm;

    public $alumno;

    // Paso 2: carreras + inscripción
    public $todasCarreras = [];

    public $carrerasSeleccionadas = [];

    public $idCarreras = [];

    public ?array $carrera = null; // carrera en edición de inscripción

    public $estado;

    public $show = false; // controla visibilidad del form de inscripción

    // Control de pasos
    public $step;

    public function mount()
    {
        $this->step = 1;
        $this->todasCarreras = Carrera::where('vigente', 1)->get();
    }

    /* ----------- Paso 1 ----------- */
    public function siguientePaso()
    {
        if ($this->step == 1) {
            $this->alumno = $this->form->validateAlumnos();
        } elseif ($this->step == 2 && empty($this->carrerasSeleccionadas)) {
            flash()
                ->option('position', 'top-center')
                ->error('Debe seleccionar al menos una carrera');
        } else {
            $this->step += 1;
        }

    }

    public function pasoAnterior()
    {
        $this->step--;
    }

    /* ----------- Paso 2: carreras ----------- */
    public function agregarInscripcion($carreraStd)
    {
        $carrera = (array) json_decode($carreraStd);
        $this->carrera = $carrera;
        Log::info($this->carrera['id']);
        // reset datos previos de inscripción
        $this->show = true;
    }

    public function eliminarCarrera($carreraId)
    {
        $this->carrerasSeleccionadas = array_filter(
            $this->carrerasSeleccionadas,
            fn ($c) => $c['id_carrera'] != $carreraId
        );
        $this->idCarreras = array_filter(
            $this->idCarreras,
            fn ($c) => $c != $carreraId
        );
    }

    public function guardarInscripcion()
    {
        $data = $this->iForm->validateInscripcion();
        $data['id_carrera'] = $this->carrera['id'];
        $this->idCarreras[] = $this->carrera['id'];
        $this->carrerasSeleccionadas[] = [
            'carrera_nombre' => $this->carrera['nombre'],
            'anio_inscripcion' => now()->year,
            'indice_libro_matriz' => $data['indice_libro_matriz'],
            'estado' => $data['estado'],
            'id_carrera' => $data['id_carrera'],
        ];
        $this->show = false;
        $this->carrera = null;
    }

    /* ----------- Paso 3: guardar todo ----------- */
    public function guardarTodo()
    {
        $alumno = Alumno::create($this->alumno);

        foreach ($this->carrerasSeleccionadas as $c) {
            Egresado::create([
                'id_alumno' => $alumno->id,
                'id_carrera' => $c['id_carrera'],
                'anio_inscripcion' => $c['anio_inscripcion'],
                'indice_libro_matriz' => $c['indice_libro_matriz'],
                'estado' => $c['estado'],
            ]);
        }

        flash()
            ->option('position', 'top-center')
            ->success('Alumno creado e inscripto correctamente.');

        $this->reset();
        $this->step = 1;
        $this->todasCarreras = Carrera::where('vigente', 1)->get();
    }

    public function render()
    {
        return view('livewire.create-alumno');
    }
}
