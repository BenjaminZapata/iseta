<?php

namespace App\Livewire;

use App\Models\Alumno;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CursadaInfiniteScroll extends Component
{
    use AuthorizesRequests;

    #[Reactive]
    public $dni;

    #[Reactive]
    public $nombre_apellido;

    public $quantity = 10;

    public $alumnos_total;

    #[Computed]
    public function alumnos()
    {
        return Alumno::query()
             // -> ajustá 'egresados' si tu tabla tiene otro nombre
            ->select('alumnos.*')
            ->has('egresadoinscripto')
            ->when(
                $this->nombre_apellido,
                fn ($q) => $q->where('nombre', 'like', "%{$this->nombre_apellido}%")
                    ->orWhere('apellido', 'like', "%{$this->nombre_apellido}%")
            )
            ->when($this->dni, fn ($q) => $q->where('dni', 'like', "%{$this->dni}%"))
            ->lazy($this->quantity)
            ->remember();
    }

    public function mount($dni, $nombre_apellido)
    {
        $this->dni = $dni;
        $this->nombre_apellido = $nombre_apellido;
    }

    public function loadMore()
    {
        $this->quantity = $this->quantity + 10;
        $this->alumnos_total = $this->alumnos()->take($this->quantity)->all();
    }

    public function render()
    {
        $this->alumnos_total = $this->alumnos()->take($this->quantity)->all();

        return view('livewire.cursada-infinite-scroll', [
            'alumnos' => $this->alumnos,
        ]);
    }
}
