<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table = "examenes";
    protected $fillable = [
        'id_mesa',
        'id_asignatura',
        'id_carrera',
        'id_alumno',
        'libro',
        'acta',
        'nota',
        'fecha',
        'aprobado'
    ];
    public $timestamps = false;
    use HasFactory;

    public function mesa(){
        return $this -> belongsTo(Mesa::class,'id_mesa');
    }

    public function carrera(){
        return $this -> belongsTo(Carrera::class,'id_carrera');
    }

    public function alumno(){
        return $this -> belongsTo(Alumno::class,'id_alumno');
    }

    public function asignatura(){
        return $this -> belongsTo(Asignatura::class,'id_asignatura');
    }

    public function fecha(){
        if( $this-> fecha ){
           return $this->fecha;
        }

        $mesa = Mesa::where('id', $this->id_mesa)
           -> first();

        if( !$mesa ) return null;

        return $mesa->fecha;
     }

    public function tipoFinal(){
        return match($this->tipo_final){
            1 => "Escrito",
            2 => "Oral",
            3 => "Promocionado",
            4 => "Equivalencia",
            default => "Sin especificar"
        };
    }

    public function nota(){
        if($this->aprobado == 3) return 'Ausente';
        else if($this->nota <= 0) return 'Aun no rendido';
        else return $this->nota;
    }

}
