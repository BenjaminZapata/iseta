<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Mesa;
use App\Models\Profesor;
use App\Services\Admin\MesasCheckerService;
use Illuminate\Http\Request;

class AdminMesasLotes extends Controller
{


    function vista(Request $request,Asignatura $asignatura){
        $siguiente = null;
        $asignaturas = $asignatura->carrera->asignaturas;
        $anterior = null;

        foreach ($asignaturas as $key=>$asig) {
            if($asig->id == $asignatura->id){
                $siguiente = $key+1;
                $anterior = $key-1;
            }
        }

        return view('Admin.Mesas.create-dual', [
            'asignatura' => $asignatura,
            'siguiente' => $siguiente<count($asignaturas)? $asignaturas[$siguiente]:null,
            'anterior' => $anterior>=0? $asignaturas[$anterior]:null,
            'profesores' => Profesor::orderBy('apellido','asc')->orderBy('apellido','asc')->get()
        ]);
    }

    function store(Request $request, Asignatura $asignatura,MesasCheckerService $mesasService){



       $data= ['id_asignatura'=>$asignatura->id,'fecha'=>null,'llamado'=>null];

        // se añade el id de la carrera al registro de mesa, ya que no viene en el formulario
        // no deberia ser necesario pero la base de datos anterior hacia uso de esta duplicidad



        // Que los profes no sean los mismos
        if(
            $request->input('prof_presidente') == $request->input('prof_vocal_1') ||
            $request->input('prof_presidente') == $request->input('prof_vocal_2') ||
            $request->input('prof_vocal_1') == $request->input('prof_vocal_2') && $request->input('prof_vocal_1') != '0'
        ){
            return redirect()->back()->with('error','Hay profesores repetidos');
        }



        if($request->input('fecha1')){

            $esDiaValido = $mesasService->esDiaHabil($request->input('fecha1'));

            if(!$esDiaValido['success']){
                return redirect()->back()->with('error', 'Llamado 1: '.$esDiaValido['mensaje'])->withInput();
            }

            $data['fecha'] = $request->input('fecha1');
            $data['llamado'] = 1;
            $llamadoYaExiste = $mesasService->llamadoYaExiste($data);

            if($llamadoYaExiste['success']){
                return redirect()->back()->with('error',$llamadoYaExiste['mensaje'])->withInput();
            }

            Mesa::create([
                'id_asignatura' => $asignatura->id,
                'llamado' => 1,
                'id_carrera' => $asignatura->carrera->id,
                'fecha' => $request->input('fecha1'),
                'prof_presidente' => $request->input('prof_presidente'),
                'prof_vocal_1' => $request->input('prof_vocal_1'),
                'prof_vocal_2' => $request->input('prof_vocal_2')
            ]);
        }

        // --------------------

        if($request->input('fecha2')){
            $esDiaValido = $mesasService->esDiaHabil($request->input('fecha2'));

            if(!$esDiaValido['success']){
                return redirect()->back()->with('error', 'Llamado 2: '.$esDiaValido['mensaje'])->withInput();
            }

            $data['fecha'] = $request->input('fecha2');
            $data['llamado'] = 2;
            $llamadoYaExiste = $mesasService->llamadoYaExiste($data);

            if($llamadoYaExiste['success']){
                return redirect()->back()->with('error',$llamadoYaExiste['mensaje'])->withInput();
            }

            Mesa::create([
                'id_asignatura' => $asignatura->id,
                'llamado' => 2,
                'id_carrera' => $asignatura->carrera->id,
                'fecha' => $request->input('fecha2'),
                'prof_presidente' => $request->input('prof_presidente'),
                'prof_vocal_1' => $request->input('prof_vocal_1'),
                'prof_vocal_2' => $request->input('prof_vocal_2')
            ]);
        }


        return redirect()->back()->with('Se crearon correctamente');

    }
}
