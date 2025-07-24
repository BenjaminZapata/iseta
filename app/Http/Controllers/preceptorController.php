<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PreceptorController extends Controller
{
 public function alumnos()
 {
  // Mostrar lista de alumnos asignados al preceptor
  return view('Admin.Preceptor.alumnos');
 }

 public function cursadas()
 {
  // Mostrar cursadas relacionadas o filtradas para el preceptor
  return view('Admin.Preceptor.cursadas');
 }

 public function mesas()
 {
  // Mostrar notificaciones específicas del preceptor
  return view('Admin.Preceptor.mesas');
 }

 public function dashboard()
 {
  return view('Admin.Preceptor.dashboard');
 }

}