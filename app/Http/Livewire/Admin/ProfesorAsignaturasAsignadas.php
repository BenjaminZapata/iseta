<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Profesor;

class ProfesorAsignaturasAsignadas extends Component
{
    public Profesor $profesor;

    protected $listeners = ['asignaturasActualizadas' => '$refresh'];

    public function render()
    {
        // Agrupamos asignaturas por carrera y luego por año
        $asignaturasPorCarrera = $this->profesor->asignaturas->groupBy(function ($a) {
            return $a->pivot->id_carrera;
        });

        return view('livewire.admin.profesor-asignaturas-asignadas', [
            'asignaturasPorCarrera' => $asignaturasPorCarrera
        ]);
    }
}
