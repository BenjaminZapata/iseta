<?php

namespace App\Services\Admin;

use App\Models\Configuracion;
use App\Models\Cursada;
use Illuminate\Support\Carbon;

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
            $cursadasLista = $cursadasLista->concat($cursadas);
        }

        return $cursadasLista;
    }

    public function regular(Cursada $cursada)
    {
        $inicio = Carbon::parse($this->config['fecha_final_rematriculacion'])->format('Y');
        $fecha_inscripto = ($cursada->anio_cursada) + 1;

        return $fecha_inscripto == $inicio;
    }

    public function esCursadaRegular()
    {
        $egresados = $this->alumno->egresadoinscripto;
        foreach ($egresados as $egresado) {
            $inscripto = $egresado->anio_inscripcion == $this->config['anio_ciclo_actual'];
            if ($inscripto) {
                return ['cursada' => $egresado->alumno->cursadas->first(), 'inscripto' => true];
            }
        }

        $cursadas = $this->cursadasCursando();

        foreach ($cursadas as $cursada) {
            if (($cursada->aprobada == '5' || $cursada->aprobada == '1' || $cursada->aprobada == '4') && ($this->regular($cursada))) {
                return ['cursada' => $cursada, 'inscripto' => false];
            }
        }

        return false;
    }
}
