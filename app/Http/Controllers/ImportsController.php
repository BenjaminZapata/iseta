<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Excel_Importar;
use Maatwebsite\Excel\HeadingRowImport;

class ImportsController extends Controller
{
 public function index()
 {
  $dbName = env('DB_DATABASE');
  $tablesQuery = DB::select('SHOW TABLES');
  $key = "Tables_in_{$dbName}";

  $tables = array_map(fn($table) => $table->$key, $tablesQuery);

  // Filtrar tablas que no se quieren importar
  $tables = array_filter($tables, function ($table) {
   return !in_array($table, [
    'migrations',
    'failed_jobs',
    'password_resets',
    'personal_access_tokens'
   ]);
  });

  return view('Admin.importar.index', compact('tables'));
 }

 public function import(Request $request)
 {
  $request->validate([
   'tabla' => 'nullable|string',
   'archivo' => 'required|file|mimes:xls,xlsx,csv|max:2048',
  ]);

  $table = $request->input('tabla');
  $file = $request->file('archivo');

  try {
   Excel::import(new Excel_Importar($table), $file);
   return back()->with('success', 'Datos importados correctamente.');
  } catch (\Exception $e) {
   return back()->with('error', 'Error al importar: ' . $e->getMessage());
  }
 }

 public function preview(Request $request)
 {
  $request->validate([
   'archivo' => 'required|file|mimes:xls,xlsx|max:2048',
  ]);

  $file = $request->file('archivo');

  // Llamás a la función que retorna los datos listos
  [$headings, $previewRows] = $this->getPreviewData($file);

  // Renderizar vista y devolver JSON
  $html = view('admin.importar.preview', compact('headings', 'previewRows'))->render();

  return response()->json(['html' => $html]);
 }

 private function getPreviewData($file): array
 {
  // Obtener encabezados crudos
  $headingsRaw = (new HeadingRowImport)->toArray($file);
  $headings = $headingsRaw[0][0] ?? [];

  // Filtrar encabezados vacíos, nulos o genéricos como 'Column1', 'Column2'
  $headings = array_filter($headings, function ($value) {
   if (empty($value)) {
    return false; // eliminar vacíos
   }
   if (preg_match('/^column\d+$/i', $value)) {
    return false; // eliminar 'Column1', etc
   }
   return true;
  });

  // Obtener todas las filas (primera hoja)
  $data = Excel::toArray(null, $file);
  $previewRowsRaw = $data[0] ?? [];

  // Tomar sólo las filas que correspondan al número de encabezados limpios
  $previewRows = [];
  foreach ($previewRowsRaw as $index => $row) {
   if ($index === 0) {
    continue; // saltar fila encabezado original
   }
   // Ajustar cada fila para que tenga la misma cantidad de columnas que encabezados filtrados
   $previewRows[] = array_slice($row, 0, count($headings));
  }

  return [$headings, $previewRows];
 }



}
