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
}
