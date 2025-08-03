<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Cursada;
use Illuminate\Http\Request;

class AdminCursadasLotes extends Controller
{
  function vista(Request $request, Asignatura $asignatura)
  {

    // Navegacion entre asignaturas
    $siguiente = null;
    $asignaturas = $asignatura->carrera->asignaturas;
    $anterior = null;

    // Recorremos para saber cuales son las siguiente y anterior
    foreach ($asignaturas as $key => $asig) {
      if ($asig->id == $asignatura->id) { // si es la actual
        $siguiente = $key + 1;
        $anterior = $key - 1;
      }
    }

    return view('Admin.Cursadas.edit-masivo', [
      'asignatura' => $asignatura,
      'siguiente' => $siguiente < count($asignaturas) ? $asignaturas[$siguiente] : null,
      'anterior' => $anterior >= 0 ? $asignaturas[$anterior] : null,
      'carreras' => Carrera::vigentes()
    ]);
  }

  function cargar(Request $request)
  {
    $data = $request->except('_token');

    foreach ($data as $idCursada => $condicion) {
      $cursada = Cursada::find($idCursada);

      if ($condicion == 'rc') {
        $cursada->condicion = 1;
        $cursada->aprobada = 3;
      } else if ($condicion == 'rd') {
        $cursada->condicion = 1;
        $cursada->aprobada = 2;
      } else if ($condicion == 'ra') {
        $cursada->condicion = 1;
        $cursada->aprobada = 1;
      } else if ($condicion == 'p') {
        $cursada->condicion = 2;
        $cursada->aprobada = 1;
      } else if ($condicion == 'e') {
        $cursada->condicion = 3;
        $cursada->aprobada = 1;
      } else if ($condicion == 'l') {
        $cursada->condicion = 0;
        $cursada->aprobada = 1;
      }
      $cursada->save();
    }
    return redirect()->back()->with('Se han aplicado los cambios');
  }
}
