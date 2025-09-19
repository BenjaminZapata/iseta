<?php

namespace App\Livewire\Forms;

use App\Models\Egresado;
use Livewire\Attributes\Validate;
use Livewire\Form;

class InscripcionForm extends Form
{
    public $anio_inscripcion;
    public $indice_libro_matriz;
    public $anio_finalizacion;
    public $estado;

    public function rules(): array
    {
        return [
            'anio_inscripcion' => 'required|integer|min:1900|max_digits:4|max:' . date('Y'),
            'indice_libro_matriz' => 'required|string|max:255',
            'anio_finalizacion' => 'nullable|integer|min:1900|max_digits:4|max:' . (date('Y') + 10),
            'estado' => 'required|integer|between:0,2',
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