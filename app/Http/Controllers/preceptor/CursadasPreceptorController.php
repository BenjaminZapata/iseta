<?php

namespace App\Http\Controllers\Preceptor;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Examen;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Correlativa;
use App\Models\Cursada;
use App\Repositories\Admin\CursadaRepository;
use App\Repositories\AdminCursadaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\log;

class CursadasPreceptorController extends BaseController
{
 public $defaultFilters = [
  'filter_carrera_id' => 0,
  'filter_asignatura' => 0,
  'filter_alumno_id' => 0,
  'filter_condicion' => 0,
  'filter_aprobada' => 0
 ];

 function __construct()
 {
  parent::__construct();
  $this->middleware('auth:admin');
 }

 public function index(Request $request, CursadaRepository $cursadaRepo)
 {
  $this->setFilters($request);
  $this->data['cursadas'] = $cursadaRepo->index($request);
  session(['return_to' => url()->previous()]);
  return view('Preceptor.Cursadas.index', $this->data);
 }

 function delete(Cursada $cursada)
 {
  $cursada->delete();
  return redirect()->route('Preceptor.cursadas.index');
 }

 function edit(Request $request, Cursada $cursada)
 {
  $nota = Examen::where('id_carrera', $cursada->id_carrera)
   ->where('id_asignatura', $cursada->id_asignatura)
   ->where('id_alumno', $cursada->id_alumno)
   ->value('nota'); // Equivalencia
  Log::debug("message", ['cursada' => $cursada]);
  return view('Preceptor.Cursadas.edit', compact('cursada') + ['nota' => $nota]);
 }

 function update(Request $request, Cursada $cursada)
 {
  $data = $request->except('_token', '_method');
  // Update logic here...
 }
}