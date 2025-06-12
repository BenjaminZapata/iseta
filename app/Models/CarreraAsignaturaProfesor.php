<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\Pivot;
class CarreraAsignaturaProfesor extends Pivot
{
    protected $table = 'carrera_asignatura_profesor';
    protected $fillable =  [
        'id_carrera',
        'id_asignatura',
        'id_profesor'
    ];
}
