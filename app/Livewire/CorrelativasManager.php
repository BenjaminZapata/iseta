<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignatura;
use App\Models\Carrera;

class CorrelativasManager extends Component
{
    public Carrera $carrera;
    public Asignatura $asignatura;
    public $posiblesCorrelativas;

    public function mount(Carrera $carrera, Asignatura $asignatura)
    {
        $this->carrera = $carrera;

        // Aseguramos que la relación 'correlatividades' esté cargada
        $this->asignatura = $asignatura->load('correlativas');

        // Solo asignaturas de años anteriores
        $this->posiblesCorrelativas = $carrera->asignaturas
            ->where('anio', '<', $asignatura->anio)
            ->sortBy(['anio', 'nombre']);
    }

    public function toggleCorrelativa($id)
    {
        if ($this->asignatura->correlatividades->contains($id)) {
            $this->asignatura->correlatividades()->detach($id);
        } else {
            $this->asignatura->correlatividades()->attach($id);
        }

        // Recargar relaciones
        $this->asignatura->refresh();
    }

    public function render()
    {
        return view('livewire.correlativas-manager');
    }
}
