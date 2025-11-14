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

    public $id_alumno;

    public $condicion;

    public $aprobada;

    public $groupId;

    public function mount($asignatura, $id_carrera, $anio_cursada, $groupId, ?int $id_alumno, ?int $condicion, ?int $aprobada)
    {
        $this->condicion = $condicion;
        $this->aprobada = $aprobada;
        $this->id_alumno = $id_alumno;
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

        return CursadaRepository::cursadasAsignatura(
            $this->id_carrera,
            $this->anio_cursada,
            $this->asignatura,
            $this->id_alumno,
            $this->condicion,
            $this->aprobada

        );
    }
}
