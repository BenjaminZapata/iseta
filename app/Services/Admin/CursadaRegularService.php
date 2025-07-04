<?php

namespace App\Services\Admin;

use App\Models\Alumno;
use Illuminate\Support\Carbon;
use App\Models\Cursada;

class CursadaRegularService
{
    protected $alumno;
    protected $config;

    public function __construct($alumno, $config)
    {
        $this->alumno = $alumno;
        $this->config = $config;
    }

    public function cursadasCursando()
    {

        $carreras = $this->alumno->carreras()
            ->where('estado', 'Cursando');


        $cursadasLista = [];
        foreach ($carreras as $carrera) {
            $cursadasLista[] = $this->alumno->cursadas()
                ->where('id_carrera', $carrera->id)
                ->get();
        }
        return $cursadasLista;
    }

    public function regular(Cursada $cursada)
    {
        $inicio = Carbon::parse($this->config['fecha_inicial_rematriculacion']);
        $final = Carbon::parse($this->config['fecha_final_rematriculacion']);
        $fecha_inscripto = Carbon::parse($cursada->created_at);
        return $fecha_inscripto->between($inicio, $final);
    }


    public function esCursadaRegular()
    {
        $cursadasLista = $this->cursadasCursando();
        foreach ($cursadasLista as $cursadas) {
            foreach ($cursadas as $cursada) {
                if (($cursada->aprobada == '5' || $cursada->aprobada == '1' || $cursada->aprobada == '4') && ($this->regular($cursada)) ){
                    return true;
                }
            }
        }
        return false;
    }
}
