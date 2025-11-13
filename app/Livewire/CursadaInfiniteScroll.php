<?php

namespace App\Livewire;

use App\Models\Alumno;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class CursadaInfiniteScroll extends Component
{
    use AuthorizesRequests, WithoutUrlPagination, WithPagination;

    public $dni;

    public $nombre_apellido;

    public function mount($dni, $nombre_apellido)
    {
        $this->dni = $dni;
        $this->nombre_apellido = $nombre_apellido;
    }

    public function render()
    {

        return view('livewire.cursada-infinite-scroll', [
            'alumnos' => Alumno::query()
                ->join('egresadoinscripto', 'egresadoinscripto.id_alumno', '=', 'alumnos.id') // -> ajustá 'egresados' si tu tabla tiene otro nombre
                ->select('alumnos.*')
                ->when(
                    $this->nombre_apellido,
                    fn ($q) => $q->where('nombre', 'like', "%{$this->nombre_apellido}%")
                        ->orWhere('apellido', 'like', "%{$this->nombre_apellido}%")
                )
                ->when($this->dni, fn ($q) => $q->where('dni', 'like', "%{$this->dni}%"))
                ->simplePaginate(10),
        ]);
    }
}
