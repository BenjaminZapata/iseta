<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class InscripcionForm extends Form
{
    public $indice_libro_matriz;

    public $estado = 0;

    public function rules(): array
    {
        return [
            'indice_libro_matriz' => 'required|string|max:8',
        ];
    }

    public function validateInscripcion(): array
    {
        $this->validate();
        $data = $this->pull();
        $this->anio_inscripcion = now()->year;

        return $data;
    }
}
