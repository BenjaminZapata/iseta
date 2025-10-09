<?php

namespace App\Livewire;

use App\Services\Admin\AdminCorrelativasService;
use App\Models\Asignatura;
use Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CorrelativaAdd extends Component
{
    public $carrera;

    public Asignatura $singleAsignatura;

    public $correlativa;

    public bool $hasCorr;

    public array $correlativasSA;

    public array $correlativasId;

    public array $corrT;

    public $showModal = false;

    public function mount($carrera, $asignatura)
    {
        $this->carrera = $carrera;
        $this->correlativasSA = $asignatura->correlativas->toArray();
        $this->hasCorr = empty($this->correlativasSA);
        $this->correlativasId = [];
        $this->singleAsignatura = $asignatura;
        foreach ($asignatura->correlativas as $corr) {
            $this->correlativasId[] = $corr->id;
        }
        $this->correlativas = [];
        $this->correlativa = '';
    }

    public function addCorrelativa()
    {

        if (empty($this->corrS)) {
            return flash()
                ->option('position', 'top-center')
                ->error('Seleccione una correlativa');
        }
        Log::debug("aca pasa");
        $asignaturaOwn = $this->singleAsignatura;
        $asignaturaCorr = Asignatura::find($this->correlativa);
        $this->correlativa = $asignaturaCorr->toArray();
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

    public function desvincularCorrelativa($asignaturaId)
    {
        app(AdminCorrelativasService::class)->eliminar($this->carrera->id, $this->singleAsignatura, $asignaturaId);
        $this->correlativasSA = array_filter($this->correlativasSA, fn ($c) => $c['id'] != $asignaturaId);
        $this->hasCorr = empty($this->correlativasSA);

    }

    public function saveCorrelativas(){
        app(AdminCorrelativasService::class)->agregar($this->singleAsignatura, $this->correlativas, $this->carrera->id);
        foreach($this->correlativas as $corr) {
            $this->correlativasSA[] = $corr;
        }
        $this->correlativas = [];
    }

    public function render()
    {
        return view('livewire.correlativa-add');
    }
}