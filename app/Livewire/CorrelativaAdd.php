<?php

namespace App\Livewire;

use App\Models\Asignatura;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CorrelativaAdd extends Component
{
    public $carrera;

    public Asignatura $singleAsignatura;

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
        $this->correlativa = (array) $this->correlativa;
        Log::debug('Asignatura a la que se quiere agregar correlativa', $this->correlativa);
        $asignaturaOwn = $this->singleAsignatura;
        $asignaturaCorr = new Asignatura($this->correlativa);
        if ($asignaturaOwn->carrera()->where('id', $this->carrera->id)->first()->pivot->anio < $asignaturaCorr->carrera()->where('id', $this->carrera->id)->first()->pivot->anio) { // una asig del 2do año, no puede tener una correlativa de 1er año ni 2do
            return flash()
                ->option('position', 'top-center')
                ->error('El año de la correlativa debe ser menor al de la asignatura');
        }
        if ($asignaturaOwn->correlativas($this->carrera->id)->where('id_asignatura', $asignaturaCorr->id)->exists()) {
            return flash()
                ->option('position', 'top-center')
                ->error('Esta asignatura ya tiene esta correlativa');
        }

        $this->correlativas[] = $this->correlativa;
    }

    public function render()
    {
        return view('livewire.correlativa-add');
    }
}
