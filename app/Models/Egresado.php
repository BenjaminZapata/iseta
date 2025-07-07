<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Egresado extends Model
{
    use HasFactory;

    protected $table = "egresadoinscripto";
    public $timestamps = false;

    protected $fillable = ['id_alumno','id_carrera','anio_inscripcion','indice_libro_matriz','anio_finalizacion','estado'];

    public function alumno(){
        return $this -> hasOne(Alumno::class,'id','id_alumno');
    }

    public function carrera(){
        return $this -> hasOne(Carrera::class,'id','id_carrera');
    }

    static function estaInscripto($carrera,$alumno=null){
        if(!$alumno) $alumno=Auth::user();

        $existe=Egresado::where('id_alumno',$alumno->id)
            -> where('id_carrera', $carrera)
            -> exists();

        return $existe;
    }

    public function estado(){
        $estado = ['Cursando','Egresado','Desertor'];
        
        if(isset($estado[$this->estado])){
            return $estado[$this->estado];
        }else{
            return 'Otro';
        }
    }

}
