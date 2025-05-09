<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarreraAsignatura extends Model
{
    use HasFactory;
    protected $table = 'carrera_asignaturas';
    public $timestamps = false;

    protected $fillable = ['id_carrera','id_asignaturas'];


}
