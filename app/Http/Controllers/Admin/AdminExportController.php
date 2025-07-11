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


class AdminExportController extends Controller
{
    function cursadasAsignatura(Request $request, Asignatura $asignatura)
    {

        $archivo = str_replace(' ', '', trim($asignatura->nombre)) . '-cursantes-' . \date('Y-m-d');
        return Excel::download(new CursadasExcelExport($asignatura), $archivo . '.xlsx');
    }

    public function cursadasCarrera(Request $request, Carrera $carrera)
    {
        $archivo = str_replace(' ', '_', trim($carrera->nombre)) . '-cursantes-' . date('Y-m-d');

        return Excel::download(
            new CursadasCarreraWrapperExport(
                $carrera,
                $request->only(['genero', 'anio', 'condicion']) // ← filtros se conservan
            ),
            $archivo . '.xlsx'
        );
    }

}
