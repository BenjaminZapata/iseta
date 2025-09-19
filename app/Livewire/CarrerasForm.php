<?php

namespace App\Livewire;

use App\Models\Carrera;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Reactive;

class CarrerasForm extends Component
{
    public $todasCarreras;

    public $carrerasSeleccionadas = [];

    #[Reactive]
    public Carrera $carrera;

    protected $listeners = [
        'carrerasSeleccionadasUpdated',
    ];

    public function mount($todasCarreras, $carrerasSeleccionadas)
    {
        $this->todasCarreras = $todasCarreras;
        $this->carrerasSeleccionadas = $carrerasSeleccionadas;
    }


    public function agregarCarrera($carrera)
    {
        $this->carrerasSeleccionadas[] = $carrera;
        $this->dispatch('agregarCarrera', $carrera);
    }

    public function agregarInscripcion($carrera)
    {
        $this->carrera = $carrera;
        Log::info('Carrera en CarrerasForm: ' . $this->carrera->id);
        $this->dispatch('abrirInscripcionForm');
    }
    public function eliminarCarrera($carreraId)
    {
        $this->carrerasSeleccionadas = array_filter($this->carrerasSeleccionadas, fn($id) => $id != $carreraId);
        $this->dispatch('eliminarCarrera', $carreraId);
    }

    public function render()
    {
        return view('livewire.carreras-form', ['carrera' => $this->carrera]);
    }
}
