<?php

namespace App\Livewire;

use Livewire\Component;
use Log;

class CorrelativaAdd extends Component
{
    public $carrera;

    public $singleAsignatura;

    public $correlativa;

    public $correlativas;

    public $showModal = false;

    public function mount($carrera, $asignatura)
    {
        $this->carrera = $carrera;
        $this->singleAsignatura = $asignatura;
        $this->correlativas = [];
    }

    public function addCorrelativa()
    {
        $this->correlativa = json_decode($this->correlativa);
        Log::debug($this->singleAsignatura->carrera->where('id', $this->carrera->id)->pivot);
        if ($this->singleAsignatura->carrera->where('id', $this->carrera->id)->pivot->anio < $this->correlativa->carrera->where('id', $this->carrera->id)->pivot->anio) { // una asig del 2do año, no puede tener una correlativa de 1er año ni 2do
            return \redirect()->back()->with('error', 'El año de la correlativa debe ser menor al de la asignatura');
        }

        /*         if ($asignatura->tieneLaCorrelativa($asigCorrelativa->id)) {  // Comprobar si ya tienes esa correlativa
                    return \redirect()->back()->with('error', 'Esta asignatura ya tiene esta correlativa');
                } */
        $this->correlativas[] = $this->correlativa;
    }

    public function render()
    {
        return view('livewire.correlativa-add');
    }
}
