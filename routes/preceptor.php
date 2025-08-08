<?php

use App\Http\Controllers\Preceptor\InscripcionPreceptorController;
use App\Http\Controllers\Preceptor\CursadasPreceptorController;
use App\Http\Controllers\Preceptor\AlumnoPreceptorController;
use App\Http\Controllers\RematriculacionController;
use App\Http\Controllers\Preceptor\ExamenPreceptorController;
use App\Http\Controllers\Preceptor\MesaPreceptorController;
use App\Http\Controllers\Admin\AdminPdfController;
use App\Http\Controllers\Preceptor\AsignaturasPreceptorController;
use App\Http\Controllers\Preceptor\CarrerasPreceptorController;

Route::prefix('preceptor')
 ->middleware(['auth:admin'])
 ->name('preceptor.')
 ->group(function () {

  // Dashboard
  Route::get('/dashboard', [AlumnoPreceptorController::class, 'dashboard'])->name('dashboard');

  // Alumnos
  Route::get('/alumnos', [AlumnoPreceptorController::class, 'index'])->name('alumnos.index');
  Route::get('/alumnos/create', [AlumnoPreceptorController::class, 'create'])->name('alumnos.create');
  Route::get('/alumnos/{alumno}/edit', [AlumnoPreceptorController::class, 'edit'])->name('alumnos.edit');
  Route::put('/alumnos/{alumno}', [AlumnoPreceptorController::class, 'update'])->name('alumnos.update');
  Route::post('/alumnos', [AlumnoPreceptorController::class, 'store'])->name('alumnos.store');
  Route::get('/alumnos/{alumno}/verificar', [AlumnoPreceptorController::class, 'verificar'])->name('alumnos.verificar');
  Route::post('/alumno/{alumno}/rematricular', [RematriculacionController::class, 'rematricular'])->name('alumno.rematricular');

  // Inscriptos
  Route::get('/inscriptos/create', [InscripcionPreceptorController::class, 'create'])->name('inscriptos.create');

  // Cursadas
  Route::get('/cursadas', [CursadasPreceptorController::class, 'index'])->name('cursadas.index');
  Route::get('/cursadas/create', [CursadasPreceptorController::class, 'create'])->name('cursadas.create');
  Route::get('/cursadas/{cursada}/edit', [CursadasPreceptorController::class, 'edit'])->name('cursadas.edit');
  Route::post('/cursadas', [CursadasPreceptorController::class, 'store'])->name('cursadas.store');

  // Exámenes
  Route::get('/examenes/{examen}/edit', [ExamenPreceptorController::class, 'edit'])->name('examenes.edit');
  Route::post('/examenes', [ExamenPreceptorController::class, 'store'])->name('examenes.store');
  Route::put('/examenes/{examen}', [ExamenPreceptorController::class, 'update'])->name('examenes.update');
  Route::post('/examenes/{examen}/nota', [ExamenPreceptorController::class, 'modificarNota'])->name('examenes.nota');
  Route::delete('/examenes/{examen}', [ExamenPreceptorController::class, 'destroy'])->name('examenes.destroy');

  // Mesas
  Route::get('/mesas', [MesaPreceptorController::class, 'index'])->name('mesas.index');
  Route::get('/mesas/create', [MesaPreceptorController::class, 'create'])->name('mesas.create');
  Route::post('/mesas', [MesaPreceptorController::class, 'store'])->name('mesas.store');
  Route::get('/mesas/{mesa}/edit', [MesaPreceptorController::class, 'edit'])->name('mesas.edit');
  Route::put('/mesas/{mesa}', [MesaPreceptorController::class, 'update'])->name('mesas.update');
  Route::delete('/mesas/{mesa}', [MesaPreceptorController::class, 'destroy'])->name('mesas.destroy');

  // Actas PDF
  Route::get('/mesas/acta-volante/{mesa}', [AdminPdfController::class, 'acta_volante'])->name('mesas.acta');
  Route::get('/mesas/acta-volante-prom/{mesa}', [AdminPdfController::class, 'actaVolantePromocion'])->name('mesas.actaprom');
  Route::get('/mesas/acta-volante-libre/{mesa}', [AdminPdfController::class, 'actaVolanteLibre'])->name('mesas.actalibre');

  // Asignaturas
  Route::get('/asignaturas/{asignatura}/edit', [AsignaturasPreceptorController::class, 'edit'])->name('asignaturas.edit');
  Route::put('/asignaturas/{asignatura}', [AsignaturasPreceptorController::class, 'update'])->name('asignaturas.update');

  // Carreras
  Route::get('/carreras/{carrera}/edit', [CarrerasPreceptorController::class, 'edit'])->name('carreras.edit');
  Route::put('/carreras/{carrera}', [CarrerasPreceptorController::class, 'update'])->name('carreras.update');

 });
