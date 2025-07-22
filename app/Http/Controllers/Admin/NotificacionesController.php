<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificacionAlumno;
use Illuminate\Http\Request;

class NotificacionesController extends Controller
{
 public function index()
 {
  $notificaciones = NotificacionAlumno::with('alumno')
   ->orderBy('fecha', 'desc')
   ->paginate(20);

  return view('admin.notificaciones.index', compact('notificaciones'));
 }

 public function leer($id)
 {
  $notificacion = NotificacionAlumno::findOrFail($id);
  $notificacion->update(['leida' => true]);

  return redirect()->route('admin.notificaciones.index')->with('success', 'Notificación marcada como leída.');
 }

 public function marcarTodas(Request $request)
 {
  NotificacionAlumno::where('leida', false)->update(['leida' => true]);

  return redirect()->route('admin.notificaciones.index')->with('success', 'Todas las notificaciones han sido marcadas como leídas.');
 }
}
