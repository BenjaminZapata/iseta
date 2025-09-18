<?php

namespace App\Livewire;

use Livewire\Component;

class AlumnoForm extends Component
{
    public $alumno = [
        'nombre' => '',
        'apellido' => '',
        'dni' => '',
        'fecha_nacimiento' => '',
        'lugar_nacimiento' => '',
        'estado_civil' => '',
        'genero' => '',
        'ciudad' => '',
        'codigo_postal' => '',
        'calle' => '',
        'casa_numero' => '',
        'dpto' => '',
        'piso' => '',
        'email' => '',
        'telefono_1' => '',
        'telefono_2' => '',
        'telefono_3' => '',
        'titulo_anterior' => '',
        'becas' => '',
        'nombre_institucion_secundario' => '',
        'titulo_secundario' => '',
        'observaciones' => '',
    ];

    public $carrerasSeleccionadas = [];
    public $step = 1;

    protected $listeners = [
        'agregarCarrera',
        'eliminarCarrera'
    ];

    public function siguientePaso()
    {
        $this->step++;
    }

    public function pasoAnterior()
    {
        $this->step--;
    }

    public function agregarCarrera($carreraId)
    {
        if (!in_array($carreraId, $this->carrerasSeleccionadas) && $carreraId) {
            $this->carrerasSeleccionadas[] = $carreraId;
        }
    }

    public function eliminarCarrera($carreraId)
    {
        $this->carrerasSeleccionadas = array_filter($this->carrerasSeleccionadas, fn($id) => $id != $carreraId);
    }

    public function guardarTodo()
    {
        $alumno = \App\Models\Alumno::create($this->alumno);
        foreach ($this->carrerasSeleccionadas as $carreraId) {
            $alumno->carreras()->attach($carreraId);
        }

        session()->flash('message', 'Alumno creado e inscrito correctamente.');
        $this->reset();
        $this->step = 1;
    }

    public function render()
    {
        return view('livewire.alumno-form');
    }
}
