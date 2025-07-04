<?php

namespace App\Services;

use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Correlativa;
use App\Models\Examen;
use Illuminate\Support\Facades\Auth;

class AlumnoInscripcionService{
    public $config;

    public function __construct() {
        $this->config = Configuracion::todas();
    }

    /**
     * -------------------------------
     * Comprueba que pueda inscribirse
     * -------------------------------
     */

    public function puedeInscribirse($mesa, $alumno){

        // Existe la mesa
        if(!$mesa) {
            return ['success'=>false, 'mensaje'=>'No se encontro la mesa'];
        }
        // No esta anotado
        if($mesa->anotado) {
            return ['success'=>false, 'mensaje'=>'Ya anotado en esta asignatura'];
        }
        // Hay 48hs habiles
        if(!$mesa->habilitada() && !(Auth::guard()->name == 'admin')) {
            return ['success'=>false, 'mensaje'=>'Ha caducado el tiempo de inscripcion'];
        }
        // Aprobo cursada y aun no el examen
        if($mesa->asignatura->aproboExamen($alumno)) return ['success'=>false, 'mensaje'=>'Ya se aprobo esta asignatura'];
        if(!$mesa->asignatura->aproboCursada($alumno)) return ['success'=>false, 'mensaje'=>'Aun no se aprobo la cursada de esta asignatura'];

        // Tiene correlativas sin rendir?
        $correlativas = Correlativa::debeExamenesCorrelativos($mesa->asignatura, $alumno);

        if($correlativas){
            $mensajes = [];

            foreach ($correlativas as $correlativa) {
                $mensajes[] = "Se debe la correlativa: $correlativa->nombre";
            }
            return ['success'=>false, 'mensaje'=>$mensajes];
        }

        // Puede anotarse :D
        return ['success'=>true, 'mensaje'=>0];
    }

    /**
     * --------------------------------------
     * Comprueba si puede bajarse de la mesa
     * --------------------------------------
     */

    public function puedeBajarse($mesa,$examen){

        // Esta inscripto en esta mesa
        if(!$examen) return ['success'=>false,'mensaje'=>'No estas inscripto en esta mesa.'];

        // Hay 24 hs habiles
        if(DiasHabiles::desdeHoyHasta($mesa->fecha) <= $this->config['horas_habiles_desinscripcion']){
            return ['success'=>false,'mensaje'=>'Timpo de desincripcion caducado.'];
        }

        // Si puede :D
        return ['success'=>true,'mensaje'=>0];
    }

    /**
     * --------------------------------------------
     * Comprueba que no haya rendido el llamado 1
     * --------------------------------------------
     */

    public function rindioLlamado1($mesa, $alumno){

        // Existe una examen llamado 1 de esta asigatura de este alumno
        $yaAnotadoAllamado1 = Examen::join('mesas','mesas.id','examenes.id_mesa')
            -> where('examenes.id_asignatura', $mesa->id_asignatura)
            -> where('mesas.llamado', 1)
            -> where('examenes.id_alumno', $alumno->id)
            -> first();

        // Es del mismo periodo que el llamado 2
        if($yaAnotadoAllamado1){
            $diferencia = DiasHabiles::desdeHoyHasta($yaAnotadoAllamado1->fecha, $mesa->fecha)*-1;
            $diferencia = $diferencia/24;

            if($diferencia>0 && $diferencia<$this->config['diferencia_llamados']){
                return true;
            }

        }
        return false;
    }

    /**
     * -----------------------------------------------------
     * Devuelve las mesas disponibles anotables por el alumno
     * -----------------------------------------------------
     */

    public function inscribiblesDelAlumno($alumno){

        // Todas las asignaturas de la carrera seleccionada del alumno
        $asignaturas = Asignatura::with('mesas.anotado')
            -> where('id_carrera', Carrera::getDefault()->id)
            -> get();

        // mesas a las que ya esta anotado
        $examenesInscriptos = Examen::select('id_mesa')
            -> where('id_alumno', $alumno->id)
            -> get() -> pluck('id_mesa') -> toArray();

        $posibles = []; // lista de mesas finales para mostrar
        $reg = [];      // elementos de $posibles -> $posibles = [$reg, $reg]

        foreach ($asignaturas as $asignatura) {
            $reg = [
                'asignatura' => null,
                'correlativas' => null,
                'yaAnotado' => null,
            ];

            // no se muestran en pantalla
            if(count($asignatura->mesas) == 0) continue;        // si la asignatura no tiene mesas
            if($asignatura->aproboExamen($alumno)) continue;    // el alumno ya aprobo el examen
            if(!$asignatura->aproboCursada($alumno)) continue;  // no aprobo la cursada

            $reg['asignatura'] = $asignatura;   // sino, si se agrega

            // Comprobar si de las mesas de la asignatura, el alumno ya esta inscripto en alguna
            foreach($asignatura->mesas as $mesa){
                if(in_array($mesa->id, $examenesInscriptos)) {
                    $reg['yaAnotado'] = $mesa;
                    break;
                }
            }

            // Si debe correlativas se registran y se muestran en pantalla
            $correlativas = Correlativa::debeExamenesCorrelativos($asignatura, $alumno);
            if($correlativas) $reg['correlativas'] = $correlativas;

            $posibles[] = $reg;
        }

        return $posibles;
    }

}
