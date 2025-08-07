<?php

namespace App\Http\Controllers\Preceptor;

use App\Models\Configuracion;
use App\Models\Examen;
use App\Models\Mesa;
use App\Repositories\Admin\InscripcionRepository;
use App\Services\AlumnoInscripcionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class InscripcionPreceptorController extends Controller
{
 public $inscripcionService;
 public $inscripcionRepo;

 public function __construct(AlumnoInscripcionService $alumnoInscripcionService, InscripcionRepository $inscripcionRepo)
 {
  $this->inscripcionService = $alumnoInscripcionService;
  $this->inscripcionRepo = $inscripcionRepo;

  $this->middleware('auth:admin');
  $this->middleware('verificado')->only([
   'inscribirse',
   'bajarse',
  ]);
 }
 public function create()
 {
  return view('Preceptor.inscripcion.create');
 }
}