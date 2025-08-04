<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Services\Filter;
use App\Services\Form;

class PreceptorController extends Controller
{

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
