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
  $tables = array_filter($tables, fn($table) => !in_array($table, [
   'migrations',
   'failed_jobs',
   'password_resets',
   'personal_access_tokens'
  ]));

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
  // Debug inicial
  \Log::info('Preview method called', [
   'tabla' => $request->input('tabla'),
   'file_name' => $request->file('archivo') ? $request->file('archivo')->getClientOriginalName() : 'no file'
  ]);

  try {
   $request->validate([
    'tabla' => 'nullable|string',
    'archivo' => 'required|file|mimes:xls,xlsx|max:2048',
   ]);

   $file = $request->file('archivo');
   $table = $request->input('tabla');

   \Log::info('Validation passed, processing file...');

   // Test simple primero
   if (!$file) {
    return response()->json(['error' => 'No se recibió archivo'], 400);
   }

   if (!$file->isValid()) {
    return response()->json(['error' => 'Archivo inválido'], 400);
   }

   // Obtener preview y columnas válidas
   [$headings, $previewRows, $validColumns] = $this->getPreviewDataAndColumns($file, $table);

   \Log::info('Data processed', [
    'headings_count' => count($headings),
    'rows_count' => count($previewRows),
    'valid_columns_count' => count($validColumns)
   ]);

   // Verificar que la vista existe
   if (!view()->exists('Admin.importar.preview')) {
    return response()->json(['error' => 'Vista preview no encontrada'], 500);
   }

   $html = view('Admin.importar.preview', compact('headings', 'previewRows', 'validColumns'))->render();

   \Log::info('HTML generated successfully', ['html_length' => strlen($html)]);

   return response()->json(['html' => $html]);

  } catch (\Illuminate\Validation\ValidationException $e) {
   \Log::error('Validation error', ['errors' => $e->errors()]);
   return response()->json(['error' => 'Error de validación: ' . implode(', ', $e->errors()['archivo'] ?? [])], 422);

  } catch (\Exception $e) {
   \Log::error('Error en preview', [
    'message' => $e->getMessage(),
    'trace' => $e->getTraceAsString()
   ]);
   return response()->json(['error' => 'Error al generar la previsualización: ' . $e->getMessage()], 500);
  }
 }

 private function getPreviewDataAndColumns($file, ?string $table = null): array
 {
  try {
   // 1️⃣ Encabezados crudos
   $headingsRaw = (new HeadingRowImport)->toArray($file);
   $headings = $headingsRaw[0][0] ?? [];

   $headings = array_values(array_filter(
    $headings,
    fn($value) => !empty($value) && !preg_match('/^column\d+$/i', $value)
   ));

   // 2️⃣ Todas las filas (primera hoja)
   $data = Excel::toArray([], $file);
   $previewRowsRaw = $data[0] ?? [];

   if (empty($headings) && !empty($previewRowsRaw)) {
    $count = count($previewRowsRaw[0] ?? []);
    $headings = array_map(fn($i) => "Columna $i", range(1, $count));
   }

   $previewRows = [];
   foreach ($previewRowsRaw as $index => $row) {
    if ($index === 0 && !empty($headingsRaw[0][0]))
     continue;
    $previewRows[] = array_slice($row, 0, count($headings));
   }

   $previewRows = array_slice($previewRows, 0, 20);

   // 3️⃣ Columnas válidas de la tabla (solo si se seleccionó)
   $validColumns = [];
   if (!empty($table)) {
    $columns = DB::getSchemaBuilder()->getColumnListing($table);
    foreach ($columns as $col) {
     $label = ucwords(str_replace('_', ' ', $col));
     $validColumns[$col] = $label;
    }
   }

   return [$headings, $previewRows, $validColumns];
  } catch (\Exception $e) {
   throw new \Exception("Error procesando Excel: " . $e->getMessage());
  }
 }

 public function processEditedImport(Request $request)
 {
  $request->validate([
   'tabla' => 'required|string',
   'data' => 'nullable|array',
   'mappings' => 'nullable|array',
  ]);

  $table = $request->input('tabla');
  $editedData = $request->input('data', []);
  $mappings = $request->input('mappings', []);

  $rowsToInsert = [];

  foreach ($editedData as $rowIndex => $cols) {
   $rowInsert = [];
   foreach ($cols as $colIndex => $value) {
    if (isset($mappings[$colIndex]) && !empty($mappings[$colIndex])) {
     $rowInsert[$mappings[$colIndex]] = $value;
    }
   }
   if (!empty($rowInsert))
    $rowsToInsert[] = $rowInsert;
  }

  try {
   if (!empty($rowsToInsert))
    DB::table($table)->insert($rowsToInsert);

   return response()->json([
    'success' => true,
    'inserted_rows' => count($rowsToInsert)
   ]);
  } catch (\Exception $e) {
   return response()->json([
    'success' => false,
    'message' => 'Error al insertar: ' . $e->getMessage()
   ], 500);
  }
 }


}
