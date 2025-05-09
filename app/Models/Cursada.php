<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cursada extends Model
{
    protected $table = 'cursadas';
    use HasFactory;

    protected $fillable = ['anio_cursada','aprobada','id_alumno','id_asignatura','condicion'];

    public function alumno(){
        return $this -> hasOne(Alumno::class,'id','id_alumno');
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
            default => 'Otro',
        };
    }

    public function aprobado(): string{
        if ($this->aprobada==1) {
            return 'Aprobada';
        }
        elseif($this->aprobada==2) {
            return 'Reprobada';
        }
        else {
            return 'Cursando';
        }
    }


}
