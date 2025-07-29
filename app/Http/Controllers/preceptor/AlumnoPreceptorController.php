<?php

namespace App\Http\Controllers\Preceptor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Services\Filter;
use App\Services\Form;

class AlumnoPreceptorController extends Controller
{
 // preceptor/alumnos
 public function alumnos(Request $request)
 {
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
   $query->where($filters['campo'], 'like', "%{$filters['filtro']}%");
  }

  $alumnos = $query->paginate(20);

  $carreras = Carrera::pluck('nombre', 'id');

  return view('Preceptor.alumnos.index', compact('alumnos', 'filters', 'carreras'));
 }

 public function crearAlumno()
 {
  return view('Preceptor.alumnos.create');
 }

 public function editAlumno($id)
 {
  $alumno = Alumno::findOrFail($id);
  $carreras = Carrera::select('id as carrera_id', 'nombre as carrera_nombre')->get();
  $cursadas = $alumno->cursadas()->with('asignatura')->get();
  $examenes = $alumno->examenes()->with('asignatura', 'mesa')->get(); // con relaciones si las tenés

  return view('preceptor.Alumnos.edit', [
   'alumno' => $alumno,
   'carreras' => $carreras,
   'cursadas' => $cursadas,
   'examenes' => $examenes
  ]);
 }

 // Actualizar datos del alumno
 public function update(Request $request, $id)
 {
  $alumno = Alumno::findOrFail($id);

  $validated = $request->validate([
   'nombre' => 'required|string|max:255',
   'apellido' => 'required|string|max:255',
   'dni' => 'required|string|max:20',
   'fecha_nacimiento' => 'required|date',
   'estado_civil' => 'nullable|string|max:100',
   // Agregá más validaciones si hace falta
  ]);

  $alumno->update($validated);

  return Redirect::route('preceptor.alumnos.edit', ['alumno' => $alumno->id])
   ->with('success', 'Datos actualizados correctamente.');
 }
}