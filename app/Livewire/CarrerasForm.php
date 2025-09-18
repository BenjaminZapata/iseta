<?php

namespace App\Livewire;

use Livewire\Component;

class CarrerasForm extends Component
{
    public $todasCarreras;

    public $alumno;
    public $carrerasSeleccionadas = [];

    protected $listeners = ['carrerasSeleccionadasUpdated'];

    public function mount($todasCarreras, $carrerasSeleccionadas)
    {
        $this->todasCarreras = $todasCarreras;
        $this->carrerasSeleccionadas = $carrerasSeleccionadas;
    }

    public function agregarCarrera($carreraId)
    {
        if ($carreraId && !in_array($carreraId, $this->carrerasSeleccionadas)) {
            $this->carrerasSeleccionadas[] = $carreraId;
            $this->dispatch('agregarCarrera', $carreraId);
        }
    }

    public function agregarInscripcion($carreras)
    {
        $this->dispatch('abrirInscripcionForm', $carreras, $alumno['id_provisorio'] ?? null);
    }
    public function eliminarCarrera($carreraId)
    {
        $this->carrerasSeleccionadas = array_filter($this->carrerasSeleccionadas, fn($id) => $id != $carreraId);
        $this->dispatch('eliminarCarrera', $carreraId);
    }

    public function render()
    {
        return view('livewire.carreras-form');
    }
}
