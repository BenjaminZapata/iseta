<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PreceptorController extends Controller
{
 public function alumnos()
 {
  // Mostrar lista de alumnos asignados al preceptor
  return view('Preceptor.alumnos.index');
 }

 public function cursadas()
 {
  // Mostrar cursadas relacionadas o filtradas para el preceptor
  return view('Preceptor.cursadas.index');
 }

 public function mesas()
 {
  // Mostrar notificaciones específicas del preceptor
  return view('Preceptor.mesas.index');
 }

 public function dashboard()
 {
  return view('Preceptor.dashboard');
 }

}