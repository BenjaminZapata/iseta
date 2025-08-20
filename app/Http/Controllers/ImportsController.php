<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
        $data = $request->data;
        $mappings = $request->mappings ?? [];
        $newColumns = $request->new_columns ?? [];

        // 1. Crear nuevas columnas si corresponde
        if ($request->create_columns && !empty($newColumns)) {
            foreach ($newColumns as $colName => $def) {
                if (!Schema::hasColumn($tabla, $colName)) {
                    \DB::statement("ALTER TABLE $tabla ADD COLUMN $colName {$def['type']} " . ($def['nullable'] ? 'NULL' : 'NOT NULL'));
                }
            }
        }

        // 2. Insertar filas
        $inserted = 0;
        foreach ($data as $row) {
            $insertData = [];

            foreach ($mappings as $idx => $dbCol) {
                if (isset($row[$idx])) {
                    $insertData[$dbCol] = $row[$idx];
                }
            }

            // también incluir columnas nuevas
            foreach ($newColumns as $colName => $def) {
                $insertData[$colName] = $row[count($row) - 1] ?? null;
            }

            if (!empty($insertData)) {
                \DB::table($tabla)->insert($insertData);
                $inserted++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Importación completada',
            'inserted_rows' => $inserted
        ]);
    }

   
public function save(Request $request)
{
    Log::info('--- SAVE IMPORT ---');
    Log::info('Request raw data: ' . $request->getContent());
    
    $tabla = $request->tabla;
    $data = $request->input('data');

    if (is_string($data)) {
        $data = json_decode($data, true);
        Log::info('Data decodificada: ', $data ?? []);
    } else {
        Log::info('Data recibida: ', $data ?? []);
    }

    if (!$tabla || empty($data)) {
        Log::error('Tabla o data vacía');
        return response()->json(['success' => false, 'message' => 'Datos incompletos'], 400);
    }

    $inserted = 0;
    foreach ($data as $row) {
        try {
            DB::table($tabla)->updateOrInsert(
                ['dni' => $row['dni'] ?? null],
                $row
            );
            $inserted++;
        } catch (\Exception $e) {
            Log::error("Error insert/update fila: " . json_encode($row) . " | " . $e->getMessage());
        }
    }

    Log::info("Filas insertadas: $inserted");

    return response()->json([
        'success' => true,
        'inserted_rows' => $inserted
    ]);
}

}
