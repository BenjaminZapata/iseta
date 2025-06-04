<?php

namespace App\Models;

use App\Services\DiasHabiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Mesa extends Model
{
    use HasFactory;

    protected $table = "mesas";

    public $timestamps = false;
    protected $fillable = ['id_carrera',
    'id_asignatura',
    'prof_presidente',
    'prof_vocal_1',
    'prof_vocal_2',
    'llamado',
    'fecha'
    ];

    // protected $casts = [
    //     'fecha' => 'datetime',
    // ];

    public function asignatura() {
    return $this->belongsTo(Asignatura::class, 'id_asignatura');
}


    public function anotado(){
        return $this -> hasOne(Examen::class,'id_mesa')
            ->where('id_alumno',Auth::id());
    }

    public function examenes(){
        return $this->hasMany(Examen::class,'id_mesa','id');
    }

    public function profesor(){
        return $this -> hasOne(Profesor::class,'id','prof_presidente');
    }

    function habilitada(){
        $horasHabiles = Configuracion::get('horas_habiles_inscripcion');

        $horasMesa = DiasHabiles::desdeHoyHasta($this->fecha);

        return $horasMesa >= $horasHabiles;
     }

    function profesorNombre($tipo, $mensaje="A confirmar"){
        $profesor = null;

        if($tipo == 'vocal1') $profesor = $this->vocal1;
        else if($tipo == 'vocal2') $profesor = $this->vocal2;
        else $profesor = $this->profesor;

        if($profesor){
            return $profesor->apellidoNombre();
        }else{
            return $mensaje;
        }
    }

    public function vocal1(){
        return $this -> hasOne(Profesor::class,'id','prof_vocal_1');
    }

    public function vocal2(){
        return $this -> hasOne(Profesor::class,'id','prof_vocal_2');
    }

}
