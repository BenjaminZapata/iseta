<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignatura;
use Illuminate\Support\Facades\Log;

class AsignaturaSelector extends Component
{
    public $asignaturas;
    public $selectedId;
    public $tipo_modulo;
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
        $asignatura = Asignatura::find($id);
        if ($asignatura) {
            $this->selectedId = $asignatura->id;
            $this->tipo_modulo = $asignatura->tipo_modulo;
            $this->carga_horaria = $asignatura->carga_horaria;
            $this->anio = $asignatura->anio;
        }
    }

    public function render()
    {
        return view('livewire.asignatura-selector');
    }
}
