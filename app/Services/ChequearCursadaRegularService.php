<?php
namespace App\Services\Admin;

use App\Models\Alumno;
use App\Models\Egresado;

class CursadaRegularService
{
    private $alumno;

    public function __construct($alumno)
    {
        $this->alumno = $alumno;
    }

    public function cursadasCursando()
    {
        $carreras = $this->alumno->carreras()
            ->where('estado', 'Cursando')
            ->get();
        return $this->alumno->cursadas()
            ->where('estado', 'Regular')
            ->get();
    }

    public function esCursadaRegular()
    {
        $carreras = $this->cursadasCursando();
        foreach ($carreras as $carrera) {
            if ($carrera->condicion = 'Regular') {
                return false;
            }
        }
        return true;
    }
}
