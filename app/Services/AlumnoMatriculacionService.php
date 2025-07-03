<?php

namespace App\Services;

use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Correlativa;
use App\Models\Cursada;
use App\Models\Examen;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AlumnoMatriculacionService{
    public $config;

    public function __construct() {
        $this->config = Configuracion::todas();
    }

    /**
     * -------------------------------
     * Comprueba que pueda inscribirse
     * -------------------------------
     */


     function matriculables($alumno, $carrera){

        // todas las materias de esa carrera
        $asignaturas = Asignatura::whereHas('carrera', function ($query) use ($carrera) {
            $query->where('id', $carrera->id);
        })->get();
        $anotables = [];


        // para cada asignatura
        foreach($asignaturas as $asignatura){

            // array para almancenar equivalencias, solo en caso de que deba equivalencias.
            $asignatura->{'equivalencias_previas'} = [];


            // Chequear que no este ya en la cursada
            $yaAnotadoEnCursada = $asignatura->estaCursando($alumno);
            if($yaAnotadoEnCursada) continue;

            $yaAprobo = $asignatura->aproboCursada($alumno);
            if($yaAprobo) continue;

            // Si la materia tiene correlativas
            $asignatura->equivalencias_previas = Correlativa::debeCursadasCorrelativos($asignatura,$alumno);


            $anotables[] = $asignatura;
        }

        return $anotables;
    }

    function validasParaRegistrar($carrera,$inputs,$alumno){

        //todas la materias de esa carrera
        $asignaturas_de_carrera = $carrera->asignaturas()->pluck('id')->toArray();

        $asignaturas=[];

        foreach($inputs as $asig_id => $value){

            // si no se selecciono ignora, si no es de la carrera
            if($value == 0) continue;

            // Si la asignatura no es de esta carrera, error
            if(!in_array($asig_id, $asignaturas_de_carrera)){
                return ['success' => false,'mensaje' => 'Ha habido un error'];
            }

            // Ver que no este ya anotado o que ya la haya aprobado
            $yaAnotadoEnCursada = Cursada::where('id_alumno', $alumno->id)
                -> whereRaw('(aprobada=3 OR aprobada=1)')
                -> where('id_asignatura', $asig_id)
                -> first();

            // Si lo esta, no incluir
            if($yaAnotadoEnCursada) {
                return ['success' => false,'mensaje' => 'Ya has cursado 1 o mas materias'];
            }

            // Obtener datos de la asignatura con sus correlativas
            $asignatura = Asignatura::with('correlativas.asignatura')->where('id', $asig_id)->first();

            // verifica equivalencias
            if(Correlativa::debeCursadasCorrelativos($asignatura,$alumno)){
                return ['success' => false,'mensaje' => 'Debes 1 o mas correlativas'];
            }

            $asignaturas[$asig_id] = $value;
        }
        return ['success' => true, 'mensaje' => $asignaturas];
    }

    function esFechaDeRematriculacion(){

        $hoy = Carbon::now();

        $inicio = Carbon::parse($this->config['fecha_inicial_rematriculacion']);
        $final = Carbon::parse($this->config['fecha_final_rematriculacion']);

        // Compara las fechas
        if ($final->greaterThan($hoy) && $inicio->lessThan($hoy)) {
           return true;
        } return false;

    }

}
