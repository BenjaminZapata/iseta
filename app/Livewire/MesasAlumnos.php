<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;
use Illuminate\Support\Collection;

class MesasAlumnos extends Component
{
    #[Url(except: '')]
    public $search = '';

    public $mesa;
    public Collection $inscribibles;
    public Collection $inscriptos;

    public function mount($mesa, $inscribibles)
    {
        $this->mesa = $mesa;

        // IDs de alumnos YA cargados en la mesa
        $this->inscriptos = collect($mesa->examenes)->pluck('id_alumno');

        // Filtrar los inscribibles EXCLUYENDO los ya inscriptos
        $this->inscribibles = collect($inscribibles)->reject(
            fn ($a) => $this->inscriptos->contains($a->id)
        );
    }

    public function getFilteredProperty()
    {
        $search = strtolower(trim($this->search));

        if ($search === '') {
            return $this->inscribibles;
        }

        return $this->inscribibles->filter(function ($alumno) use ($search) {
            return str_contains(strtolower($alumno->apellido), $search)
                || str_contains(strtolower($alumno->nombre), $search)
                || str_contains((string) $alumno->dni, $search);
        });
    }

    public function render()
    {
        return view('livewire.mesas-alumnos', [
            'filtered' => $this->filtered,
        ]);
    }
}
