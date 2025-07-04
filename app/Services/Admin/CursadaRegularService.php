<?php

namespace App\Services\Admin;

use App\Models\Alumno;
use Illuminate\Support\Carbon;
use App\Models\Cursada;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Log;

class CursadaRegularService
{
    protected $alumno;
    protected $config;

    public function __construct($alumno)
    {
        $this->alumno = $alumno;
        $this->config = Configuracion::todas();
    }

    public function cursadasCursando()
    {
        $carreras = $this->alumno->carreras()
        ->where('estado', 0)
        ->get();


        $cursadasLista = collect();
        foreach ($carreras as $carrera) {
            $cursadas = $this->alumno->cursadas()
                ->where('id_carrera', $carrera->id_carrera)
                ->get();
            log::debug($carrera);
            $cursadasLista = $cursadasLista->concat($cursadas);
        }
        log::debug($cursadasLista);
        return $cursadasLista;
    }

    public function regular(Cursada $cursada)
    {
       // $inicio = Carbon::parse($this->config['fecha_inicial_rematriculacion']);
      //  $final = Carbon::parse($this->config['fecha_final_rematriculacion']);
      //  $fecha_inscripto = Carbon::parse($cursada->created_at);
        $inicio = Carbon::parse($this->config['fecha_final_rematriculacion'])->format('Y');
        $fecha_inscripto = ($cursada->anio_cursada)+1;
        Log::debug("año inscripto", ['message' => $fecha_inscripto]);
       // $fecha_inscripto->addYear();
       // error_log($fecha_inscripto);
        //return $fecha_inscripto->between($inicio, $final);
        Log::debug("año rematriculacion", ['message' => $inicio]);
        return $fecha_inscripto == $inicio;

    }


    public function esCursadaRegular()
    {
        Log::debug("inicio");
        $cursadas = $this->cursadasCursando();

        Log::debug($cursadas);
        foreach ($cursadas as $cursada) {
            Log::debug("1º foreach");
            if (($cursada->aprobada == '5' || $cursada->aprobada == '1' || $cursada->aprobada == '4') && ($this->regular($cursada)) ){
                return true;
            }
        }
        return false;
    }
}
