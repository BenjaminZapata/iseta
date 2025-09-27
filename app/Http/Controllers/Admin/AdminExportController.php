<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CursadasCarreraWrapperExport;
use App\Exports\CursadasCarreraExcelExport;
use App\Exports\CursadasExcelExport;
use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;


class AdminExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    function cursadasAsignatura(Request $request, Asignatura $asignatura)
    {

        $archivo = str_replace(' ', '', trim($asignatura->nombre)) . '-cursantes-' . \date('Y-m-d');
        return Excel::download(new CursadasExcelExport($asignatura), $archivo . '.xlsx');
    }

    public function cursadasCarrera(Request $request, Carrera $carrera)
    {
        $archivo = str_replace(' ', '_', trim($carrera->nombre)) . '-cursantes-' . date('Y-m-d');

        $filtros = [
            'genero' => strtolower($request->input('genero', '')),
            'anio' => $request->input('anio', ''),
            'condicion' => strtolower($request->input('condicion', '')),
            'asignatura_id' => $request->input('asignatura_id')
        ];
        if ($carrera->cursadas->IsNotEmpty()) {
            return Excel::download(new CursadasCarreraWrapperExport($carrera, $filtros), $archivo . '.xlsx');
        }
        return redirect()->back()->with('error', 'La carrera no tiene cursadas.');
    }


    public function mostrarFormularioExportacionCursadas(Carrera $carrera)
    {
        $aniosCalendario = \DB::table('cursadas')
            ->where('id_carrera', $carrera->id)
            ->whereNotNull('anio_cursada')
            ->select('anio_cursada')
            ->distinct()
            ->orderByDesc('anio_cursada')
            ->pluck('anio_cursada')
            ->toArray();

        return view('Admin.Exportar.cursadas', compact('carrera', 'aniosCalendario'));
    }
}