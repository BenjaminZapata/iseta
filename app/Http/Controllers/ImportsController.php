<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class ImportsController extends Controller
{
    public function index()
    {
        // Vista inicial con formulario de subir archivo y elegir tabla destino
        $tablas = \DB::select('SHOW TABLES');
        return view('Admin.importar.index', compact('tablas'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
            'tabla'   => 'required|string'
        ]);

        $tabla = $request->tabla;

        // Leer headings (encabezados de Excel)
        $headings = (new HeadingRowImport)->toArray($request->file('archivo'))[0][0];

        // Previsualizar primeras filas
        $data = Excel::toArray(null, $request->file('archivo'));
        $rows = array_slice($data[0], 1, 10); // 10 filas como preview

        // Columnas válidas de la tabla en DB
        $validColumns = [];
        $columns = Schema::getColumnListing($tabla);
        foreach ($columns as $col) {
            $validColumns[$col] = ucfirst(str_replace('_', ' ', $col));
        }

        // Guardamos temporalmente en sesión para usar en processEditedImport
        Session::put('import_data', [
            'tabla' => $tabla,
            'rows' => $data[0],
            'headings' => $headings
        ]);

        return view('Admin.importar.preview', [
            'tabla'        => $tabla,
            'headings'     => $headings,
            'previewRows'  => $rows,
            'validColumns' => $validColumns
        ]);
    }

    public function processEditedImport(Request $request)
{
    $tabla = $request->tabla;
    $data = $request->input('data');
    $mappings = $request->mappings ?? [];
    $newColumns = $request->new_columns ?? [];

    Log::info("PROCESS - Tabla destino: {$tabla}");
    Log::info("PROCESS - Data recibida:", [$data]);

    // 🔧 FIX: aplanar doble array
    if (isset($data[0]) && is_array($data[0]) && count($data) === 1) {
        $data = $data[0];
        Log::info("PROCESS - Data aplanada:", [$data]);
    }

    $inserted = 0;

    try {
        foreach ($data as $row) {
            $insertData = [];

            foreach ($mappings as $idx => $dbCol) {
                if (isset($row[$idx])) {
                    $insertData[$dbCol] = $row[$idx];
                }
            }

            foreach ($newColumns as $colName => $def) {
                $insertData[$colName] = $row[count($row) - 1] ?? null;
            }

            Log::info("PROCESS - Insertando fila:", $insertData);

            if (!empty($insertData)) {
                DB::table($tabla)->insert($insertData);
                $inserted++;
            }
        }

    } catch (\Exception $e) {
        Log::error("PROCESS - Error general:", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }

    Log::info("PROCESS - Total insertadas: {$inserted}");

    return response()->json([
        'success' => true,
        'message' => 'Importación completada',
        'inserted_rows' => $inserted
    ]);
}


   
public function save(Request $request)
{
    $tabla = $request->tabla;
    $data = $request->input('data');

    // Si es JSON string, decodificamos
    if (is_string($data)) {
        $data = json_decode($data, true);
    }

    // Si viene doblemente anidado [[...]], lo aplanamos
    if (is_array($data) && isset($data[0]) && is_array($data[0]) && array_keys($data[0]) !== range(0, count($data[0]) - 1)) {
        // caso normal: [{...}, {...}]
    } elseif (is_array($data) && isset($data[0]) && is_array($data[0])) {
        $data = $data[0]; // aplanar un nivel
    }

    \Log::info("PROCESS - Data final lista para insertar:", $data);

    $inserted = 0;
    foreach ($data as $row) {
        \Log::info("PROCESS - Insertando fila:", $row);

        DB::table($tabla)->updateOrInsert(
            ['dni' => $row['dni'] ?? null],
            $row
        );

        $inserted++;
    }

    \Log::info("PROCESS - Total insertadas: " . $inserted);

    return response()->json([
        'success' => true,
        'inserted_rows' => $inserted
    ]);
}


}