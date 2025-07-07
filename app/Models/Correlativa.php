<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Correlativa extends Model
{
    use HasFactory;
    protected $table = "correlatividades";
    protected $fillable = ['id_asignatura','asignatura_correlativa'];
    public $timestamps = false;

    public function asignatura(){
        return $this->BelongsTo(Asignatura::class,'asignatura_correlativa','id');
    }

    public static function debeExamenesCorrelativos($asignatura, $alumno){
        if(!$alumno) {
            $alumno=Auth::user();
        }
        $asignatura = Asignatura::with('correlativas.asignatura')
        ->where('id', $asignatura->id)
        ->first();

        $sinAprobar = [];

        foreach($asignatura->correlativas as $correlativa){
           $asigCorr = $correlativa->asignatura;
           if(!$asigCorr) {
                return false;
           }
           if($asigCorr->aproboExamen($alumno)) {
				continue;
		   }
           else $sinAprobar[] = $asigCorr;
        }

        if(count($sinAprobar)>0) {
            return $sinAprobar;
        }
        else return false;
     }

    public static function debeCursadasCorrelativos($asignatura, $alumno){
        if(!$alumno) {
            $alumno=Auth::user();
        }
        $asignatura = Asignatura::with('correlativas.asignatura')
        ->where('id', $asignatura->id)
        ->first();

        $sinAprobar = [];

        foreach($asignatura->correlativas as $correlativa){
           $asigCorr = $correlativa->asignatura;
           if(is_null($asigCorr)) {
                return false;
           }
           log:debug($asigCorr->aproboCursada($alumno));
           if($asigCorr->aproboCursada($alumno)) {
                continue;
           } else
           $sinAprobar[] = $asigCorr;
        }

        if(count($sinAprobar)>0) {
            return $sinAprobar;
        }
        return false;
     }

}
