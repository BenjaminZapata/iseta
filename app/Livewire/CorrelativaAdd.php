<?php

namespace App\Livewire;

use App\Models\Asignatura;
use Livewire\Component;

class CorrelativaAdd extends Component
{
    public $carrera;

    public Asignatura $singleAsignatura;

    public $corrS;

    public array $correlativasId;

    public array $corrT;

    public $showModal = false;

    public function mount($carrera, $asignatura)
    {
        $this->carrera = $carrera;
        $this->correlativasId = [];
        $this->singleAsignatura = $asignatura;
        foreach ($asignatura->correlativas as $corr) {
            $this->correlativasId[] = $corr->id;
        }
        $this->corrT = [];
    }

    public function addCorrelativa()
    {

        if (empty($this->corrS)) {
            return flash()
                ->option('position', 'top-center')
                ->error('Seleccione una correlativa');
        }
        $this->corrS = json_decode($this->corrS);
        $this->corrS = (array) $this->correlativas;
        $asignaturaOwn = $this->singleAsignatura;
        $asignaturaCorr = new Asignatura($this->corrS);
        if ($asignaturaOwn->carrera()->where('id', $this->carrera->id)->first()->pivot->anio < $asignaturaCorr->carrera()->where('id', $this->carrera->id)->first()->pivot->anio) { // una asig del 2do año, no puede tener una correlativa de 1er año ni 2do
            return flash()
                ->option('position', 'top-center')
                ->error('El año de la correlativa debe ser menor al de la asignatura');
        }
        if ($asignaturaOwn->correlativas()->where('id_asignatura', $asignaturaCorr->id)->exists()) {
            return flash()
                ->option('position', 'top-center')
                ->error('Esta asignatura ya tiene esta correlativa');
        }
        $this->corrT[] = $this->corrS;
        $this->correlativasId[] = $this->corrS['id'];
        $this->corrS = '';
    }

    public function deleteCorrelativa($asignaturaId)
    {
        $this->correlativas = array_filter(
            $this->corrT,
            fn ($c) => $c['id'] != $asignaturaId
        );
        $this->correlativasId = array_filter(
            $this->correlativasId,
            fn ($c) => $c != $asignaturaId
        );
    }

    public function render()
    {
        return view('livewire.correlativa-add');
    }
}
