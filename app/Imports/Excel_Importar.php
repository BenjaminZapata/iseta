<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class Excel_Importar implements ToCollection
{
 protected $table;

 public function __construct($table)
 {
  $this->table = $table;
 }

 public function collection(Collection $rows)
 {
  if ($rows->isEmpty()) {
   return;
  }

  $header = $rows->shift(); // Primera fila como encabezado

  foreach ($rows as $row) {
   $data = [];
   foreach ($header as $index => $column) {
    $data[$column] = $row[$index] ?? null;
   }
   DB::table($this->table)->insert($data);
  }
 }
}
