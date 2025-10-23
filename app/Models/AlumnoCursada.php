<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AlumnoCursada extends Pivot
{
    protected $table = 'alumno_cursada';

    public $timestamps = true;

    protected $fillable = [
        'id_alumno',
        'id_cursada',
        'aprobada',
        'condicion',
    ];
}
