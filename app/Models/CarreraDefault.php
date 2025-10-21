<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarreraDefault extends Model
{
    use HasFactory;

    protected $table = 'carreras_default';

    protected $fillable = ['id_carrera', 'id_alumno'];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }
}
