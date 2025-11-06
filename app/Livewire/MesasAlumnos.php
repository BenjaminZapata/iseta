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

    public function mount($mesa, $inscribibles)
    {
        $this->mesa = $mesa;
        $this->inscribibles = collect($inscribibles);
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
