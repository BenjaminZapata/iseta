<?php

namespace App\Livewire;

use App\Repositories\Admin\CursadaRepository;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ArbolCursadas extends Component
{
    public $grupo = '';

    public $idCarreraAnio;

    public $showCursadas = false;

    public $primera;

    public $agrupadas;

    public function mount($grupo, $idCarreraAnio, $primera)
    {
        $this->primera = $primera;
        $this->grupo = $grupo;
        $this->idCarreraAnio = $idCarreraAnio;
    }

    public function render()
    {
        return view('livewire.arbol-cursadas');
    }

    #[computed]
    public function Asignaturas()
    {
        return CursadaRepository::asignaturaCarreraAnio($this->grupo[0]);
    }
}
