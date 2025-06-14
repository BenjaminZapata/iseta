<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cursada extends Model
{
    protected $table = 'cursadas';
    use HasFactory;

    protected $fillable = ['anio_cursada','aprobada','id_alumno','id_asignatura','id_carrera','condicion'];

    public function alumno(){
        return $this -> hasOne(Alumno::class,'id','id_alumno');
    }

    public function carrera(): BelongsTo{
        return $this -> belongsTo(Carrera::class,'id_carrera','id');
    }

    public function asignatura(){
        return $this -> belongsTo(Asignatura::class,'id_asignatura','id');
    }


    public function condicionString(): string{
        return match ($this->condicion) {
            0 => 'Libre',
            1 => 'Regular',
            2 => 'Promocion',
            3 => 'Equivalencia',
            4 => 'Desertor',
            5 => 'Itinerante',
            6 => 'Oyente',
            default => 'Otro',
        };
    }

    public function aprobado(): string{
        return match($this->aprobada) {
            1 => 'Aprobada',
            2 => 'Reprobada',
            default => 'Cursando',
        };
    }
}
