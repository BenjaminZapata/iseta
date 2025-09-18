<?php

namespace App\Livewire;

use App\Livewire\Forms\AlumnoForm;
use Livewire\Component;
use Illuminate\Validation\Rule;


class CreateAlumno extends Component
{
    public $alumno;
    public $carrerasSeleccionadas = [];
    public $step = 1;

    public AlumnoForm $form;
    protected $listeners = [
        'agregarCarrera',
        'eliminarCarrera',
        'agregarInscripcion'
    ];



    public function siguientePaso()
    {
        if ($this->step == 1) {
            $this->alumno = $this->form->validateAlumnos();
            $this->step++;
        } else {
            $this->step++;
        }
    }

    public function pasoAnterior()
    {
        $this->step--;
    }

    public function agregarCarrera($carreraId)
    {
        if (!in_array($carreraId, $this->carrerasSeleccionadas) && $carreraId) {
            $this->carrerasSeleccionadas[] = $carreraId;
        }
    }

    public function eliminarCarrera($carreraId)
    {
        $this->carrerasSeleccionadas = array_filter($this->carrerasSeleccionadas, fn($id) => $id != $carreraId);
    }

    public function guardarTodo()
    {
        $alumno = $this->alumno->save();
        foreach ($this->carrerasSeleccionadas as $carreraId) {
        }

        session()->flash('message', 'Alumno creado e inscrito correctamente.');
        $this->reset();
        $this->step = 1;
    }

    public function render()
    {
        return view('livewire.create-alumno');
    }
}
