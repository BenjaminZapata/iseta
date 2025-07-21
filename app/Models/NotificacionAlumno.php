<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificacionAlumno extends Model
{
    protected $table = 'notificaciones_alumnos';

    protected $fillable = [
        'id_alumno',
        'tipo',
        'mensaje',
        'fecha',
        'leido',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno');
    }
}
