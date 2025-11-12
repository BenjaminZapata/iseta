<?php

namespace App\Livewire;

use App\Repositories\Admin\CursadaRepository;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CursadasRequest extends Component
{
    public $asignatura;

    public $id_carrera;

    public $anio_cursada;

    public $groupId;

    public function mount($asignatura, $id_carrera, $anio_cursada, $groupId)
    {
        $this->groupId = $groupId;
        $this->asignatura = $asignatura;
        $this->id_carrera = $id_carrera;
        $this->anio_cursada = $anio_cursada;
    }

    public function render()
    {
        return view('livewire.cursadas-request');
    }

    #[Computed]
    public function Cursadas()
    {

        return CursadaRepository::cursadasAsignatura($this->id_carrera, $this->anio_cursada, $this->asignatura);
    }
}
