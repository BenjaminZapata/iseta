<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Services\Filter;
use App\Services\Form;

class PreceptorController extends Controller
{
 // preceptor/alumnos
 public function alumnos(Request $request)
 {
  // Recibir filtros con nombres coherentes
  $filters = [
   'filter_carrera_id' => $request->input('filter_carrera_id', 0),
   'filter_ciudad' => $request->input('filter_ciudad', ''),
   'filter_estado_civil' => $request->input('filter_estado_civil', ''),
   'campo' => $request->input('campo', ''),
   'filtro' => $request->input('filtro', ''),
  ];

  $query = Alumno::query();

  if (!empty($filters['filter_carrera_id']) && $filters['filter_carrera_id'] != 0) {
   $query->where('id_carrera', $filters['filter_carrera_id']);
  }

  if (!empty($filters['filter_ciudad'])) {
   $query->where('ciudad', $filters['filter_ciudad']);
  }

  if (!empty($filters['filter_estado_civil'])) {
   $query->where('estado_civil', $filters['filter_estado_civil']);
  }

  if (!empty($filters['campo']) && !empty($filters['filtro'])) {
   $query->where($filters['campo'], 'like', '%' . $filters['filtro'] . '%');
  }

  $alumnos = $query->paginate(25)->withQueryString();

  // Servicios y modelos para filtros
  $filter = new Filter;
  $form = new Form;
  $alumnoM = new Alumno();
  $carreraM = new Carrera();

  return view('Preceptor.alumnos.index', compact(
   'filters',
   'filter',
   'form',
   'alumnoM',
   'carreraM',
   'alumnos',
   'ciudades'
  ));
 }


 public function crearAlumno()
 {
  return view('Preceptor.alumnos.create');
 }

 public function editAlumno($alumno)
 {
  $alumno = Alumno::findOrFail($alumno);
  return view('Preceptor.alumnos.edit', compact('alumno'));
 }

 public function cursadas()
 {
  return view('Preceptor.cursadas.index');
 }

 public function mesas()
 {
  return view('Preceptor.mesas.index');
 }

 public function dashboard()
 {
  return view('Preceptor.dashboard');
 }
}
