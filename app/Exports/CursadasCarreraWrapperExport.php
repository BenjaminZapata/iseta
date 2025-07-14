<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CursadasCarreraWrapperExport implements WithMultipleSheets
{
 protected $carrera;
 protected $filtros;

 public function __construct($carrera, $filtros = [])
 {
  $this->carrera = $carrera;
  $this->filtros = $filtros;
 }

 public function sheets(): array
 {
  $sheets = [];

  foreach ($this->carrera->asignaturas as $asignatura) {
   if (isset($this->filtros['asignatura_id']) && $this->filtros['asignatura_id'] != $asignatura->id) {
    continue;
   }
   $sheets[] = new CursadasCarreraExcelExport($this->carrera, $this->filtros);
  }

  // Si no hay asignaturas o no pasó el filtro, agregamos la hoja igual
  if (empty($sheets)) {
   $sheets[] = new CursadasCarreraExcelExport($this->carrera, $this->filtros);
  }

  return $sheets;
 }



}
