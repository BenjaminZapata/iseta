<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CarreraAsignaturaProfesor extends Pivot
{
    protected $table = 'carrera_asignatura_profesor';

    protected $fillable = [
        'id_carrera',
        'id_asignatura',
        'id_profesor',
        'tipo_modulo',
        'carga_horaria',
        'anio',
    ];

    public function anioStr(): string
    {

        $strings = ['Primer año', 'Segundo año', 'Tercer año', 'Cuarto año', 'Quinto año', 'Sexto año'];

        return $strings[$this->anio];
    }
}
