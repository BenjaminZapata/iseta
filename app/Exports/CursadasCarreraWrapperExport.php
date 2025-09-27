<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Carrera;

class CursadasCarreraWrapperExport implements WithMultipleSheets
{
 protected $carrera;
 protected $filtros;

 public function __construct(Carrera $carrera, array $filtros = [])
 {
  $this->carrera = $carrera;
  $this->filtros = $filtros;
 }

 public function sheets(): array
 {
  $sheets = [];
  // Si se especifica asignatura_id, solo generamos una hoja
  if (!empty($this->filtros['asignatura_id'])) {
   $sheets[] = new CursadasCarreraExcelExport($this->carrera, $this->filtros);
  } else {
   // Si no hay asignatura específica, generamos una hoja por asignatura
   foreach ($this->carrera->asignaturas as $asignatura) {
    $filtrosConAsignatura = array_merge($this->filtros, ['asignatura_id' => $asignatura->id]);
    $sheets[] = new CursadasCarreraExcelExport($this->carrera, $filtrosConAsignatura);
   }
  }

  return $sheets;
 }
}