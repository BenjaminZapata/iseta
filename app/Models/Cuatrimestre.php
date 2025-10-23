<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuatrimestre extends Model
{
    protected $table = 'cuatrimestre';

    public $timestamps = true;

    protected $fillable = [
        'id_alumno',
        'id_cursada',
        'nota',
    ];
}
