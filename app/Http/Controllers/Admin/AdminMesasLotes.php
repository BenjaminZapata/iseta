<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Mesa;
use App\Models\Carrera;
use App\Models\Profesor;
use App\Services\Admin\MesasCheckerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminMesasLotes extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Muestra la vista de crear mesas en lote para una asignatura y una carrera en particular.
     *
     * @param Asignatura $asignatura La asignatura para la que se crean las mesas.
     * @param Carrera $carrera La carrera a la que pertenece la asignatura.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View La vista con los datos de la asignatura y sus profesores.
     */
    function vista(Carrera $carrera, Asignatura $asignatura)
    {
        Log::debug($carrera);
        $siguiente = null;
        $asignaturas = $carrera->asignaturas->all();
        $anterior = null;


        foreach ($asignaturas as $key => $asig) {
            if ($asig->id == $asignatura->id) {
                $siguiente = $key + 1;
                $anterior = $key - 1;
            }
        }

        return view('Admin.Mesas.create-dual', [
            'carrera' => $carrera,
            'asignatura' => $asignatura,
            'siguiente' => $siguiente < count($asignaturas) ? $asignaturas[$siguiente] : null,
            'anterior' => $anterior >= 0 ? $asignaturas[$anterior] : null,
            'profesores' => Profesor::orderBy('apellido', 'asc')->orderBy('apellido', 'asc')->get()
        ]);
    }

    function store(Request $request, Carrera $carrera, Asignatura $asignatura, MesasCheckerService $mesasService)
    {



        $data = ['id_asignatura' => $asignatura->id, 'fecha' => null, 'llamado' => null];

        // Que los profes no sean los mismos
        if (
            $request->input('prof_presidente') == $request->input('prof_vocal_1') ||
            $request->input('prof_presidente') == $request->input('prof_vocal_2') ||
            $request->input('prof_vocal_1') == $request->input('prof_vocal_2') && $request->input('prof_vocal_1') != '0'
        ) {
            return redirect()->back()->with('error', 'Hay profesores repetidos');
        }



        if ($request->input('fecha1')) {

            $esDiaValido = $mesasService->esDiaHabil($request->input('fecha1'));

            if (!$esDiaValido['success']) {
                return redirect()->back()->with('error', 'Llamado 1: ' . $esDiaValido['mensaje'])->withInput();
            }

            $data['fecha'] = $request->input('fecha1');
            $data['llamado'] = 1;
            $llamadoYaExiste = $mesasService->llamadoYaExiste($data);

            if ($llamadoYaExiste['success']) {
                return redirect()->back()->with('error', $llamadoYaExiste['mensaje'])->withInput();
            }

            Mesa::create([
                'id_asignatura' => $asignatura->id,
                'llamado' => 1,
                'id_carrera' => $carrera->id,
                'fecha' => $request->input('fecha1'),
                'prof_presidente' => $request->input('prof_presidente'),
                'prof_vocal_1' => $request->input('prof_vocal_1'),
                'prof_vocal_2' => $request->input('prof_vocal_2')
            ]);
        }

        // --------------------

        if ($request->input('fecha2')) {
            $esDiaValido = $mesasService->esDiaHabil($request->input('fecha2'));

            if (!$esDiaValido['success']) {
                return redirect()->back()->with('error', 'Llamado 2: ' . $esDiaValido['mensaje'])->withInput();
            }

            $data['fecha'] = $request->input('fecha2');
            $data['llamado'] = 2;
            $llamadoYaExiste = $mesasService->llamadoYaExiste($data);

            if ($llamadoYaExiste['success']) {
                return redirect()->back()->with('error', $llamadoYaExiste['mensaje'])->withInput();
            }

            Mesa::create([
                'id_asignatura' => $asignatura->id,
                'llamado' => 2,
                'id_carrera' => $carrera->id,
                'fecha' => $request->input('fecha2'),
                'prof_presidente' => $request->input('prof_presidente'),
                'prof_vocal_1' => $request->input('prof_vocal_1'),
                'prof_vocal_2' => $request->input('prof_vocal_2')
            ]);
        }


        return redirect()->back()->with('Se crearon correctamente');
    }
}
