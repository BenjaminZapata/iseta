<?php

namespace App\Livewire;

use Livewire\Component;

class AsignaturaSelector extends Component
{
    public $asignaturas;

    public $selectedId;

    public $carga_horaria;

    public $anio;

    public $carrera;

    public function mount($asignaturas, $carrera)
    {
        $this->asignaturas = $asignaturas;
        $this->carrera = $carrera;
    }

    public function updatedSelectedId($value)
    {
        $this->updateAsignatura($value);
    }

    public function updateAsignatura($id)
    {
        $asignatura = $this->asignaturas->find($id);

        if ($asignatura) {
            $this->selectedId = $asignatura->id;
            $this->carga_horaria = $asignatura->carga_horaria;
        }
    }

    public function render()
    {
        return view('livewire.asignatura-selector');
    }
}
