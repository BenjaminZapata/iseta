<?php

namespace App\Livewire;

use App\Models\Carrera;
use Livewire\Component;
use Illuminate\support\Facades\Log;
use Livewire\Attributes\On;

class InscripcionForm extends Component
{
    public Carrera $carrera; // para mostrar nombre carrera en el formulario
    public $anio_inscripcion;
    public $indice_libro_matriz;
    public $anio_finalizacion;

    public $estado;

    public $show = false; // para controlar visibilidad

    public function mount($carrera)
    {
        $this->carrera = $carrera;
    }
    public function rules()
    {
        return [
            'anio_inscripcion' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'indice_libro_matriz' => ['nullable', 'string', 'max:50'],
            'anio_finalizacion' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'estado' => ['required', 'integer'],
        ];
    }

    #[On('abrirInscripcionForm')]
    public function abrirInscripcionForm()
    {
        $this->reset(); // limpia datos previos
        log::info('Carrera recibida en InscripcionForm: ' . $this->carrera->id);
        $this->show = true;
    }

    public function guardar()
    {
        $this->validate();
        $this->dispatch('agregarCarrera', [
            'id_carrera' => $this->carrera->id,
            'carrera_nombre' => $this->carrera->nombre,
            'anio_inscripcion' => $this->anio_inscripcion,
            'indice_libro_matriz' => $this->indice_libro_matriz,
            'anio_finalizacion' => $this->anio_finalizacion,
            'estado' => $this->estado,
        ]);

        $this->show = false;
    }

    public function render()
    {
        return view('livewire.inscripcion-form');
    }
}
