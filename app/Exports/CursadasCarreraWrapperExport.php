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
  return [
   new CursadasCarreraExcelExport($this->carrera, $this->filtros)
  ];
 }
}
