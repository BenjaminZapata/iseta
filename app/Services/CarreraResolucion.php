<?php

namespace APP\Services;

use App\Models\Carrera;
use App\Models\configuracion;
use APP\Repositories\Admin\CarreraRepository;

class CarreraResolucion

{
    /**
     *obtener las resoluciones de las carreras
     *
     * @param Carrera $carrera
     * @return array
     */

     public function GETResolucion(Carrera $carrera): array
  {
    return Carrera::where('id', $carrera->id)
        ->select('nombre','resolucion', 'vigente', 'resolucion_archivo')
        ->first();
  }
}


