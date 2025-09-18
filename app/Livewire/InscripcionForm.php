<?php

namespace App\Livewire;

use Livewire\Component;

class InscripcionForm extends Component
{
    public $id_alumno;
    public $id_carrera;
    public $anio_inscripcion;
    public $indice_libro_matriz;
    public $anio_finalizacion;
    public $estado;

    public $show = false; // para controlar visibilidad
    protected $listeners = ['abrirInscripcionForm'];

    public function rules()
    {
        return [
            'id_alumno' => ['required', 'integer'],
            'id_carrera' => ['required', 'integer'],
            'anio_inscripcion' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'indice_libro_matriz' => ['nullable', 'string', 'max:50'],
            'anio_finalizacion' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 10)],
            'estado' => ['required', 'integer'],
        ];
    }

    public function abrirInscripcionForm($idCarrera, $id_provisorio)
    {
        $this->reset(); // limpia datos previos
        $this->id_carrera = $idCarrera;
        $this->id_alumno = $id_provisorio;
        $this->show = true;
    }

    public function guardar()
    {
        $this->validate();

        $this->dispatch('agregarInscripcion', [
            'id_alumno' => $this->id_alumno,
            'id_carrera' => $this->id_carrera,
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
