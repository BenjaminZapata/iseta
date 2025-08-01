<?php
use App\Http\Controllers\Preceptor\InscripcionPreceptorController;
use App\Http\Controllers\Preceptor\CursadasPreceptorController;
use App\Http\Controllers\preceptor\AlumnoPreceptorController;
use App\Http\Controllers\Admin\AlumnoCrudController;
use App\htpp\Controllers\RematriculacionController;

Route::prefix('preceptor')
 ->middleware(['auth:admin'])
 ->name('preceptor.')
 ->group(function () {
  Route::get('/dashboard', [AlumnoPreceptorController::class, 'dashboard'])->name('dashboard');

  // Rutas alumnos
  Route::get('/alumnos', [AlumnoPreceptorController::class, 'alumnos'])->name('alumnos.index');
  Route::get('/alumnos/create', [AlumnoPreceptorController::class, 'crearAlumno'])->name('alumnos.create');
  Route::get('/alumnos/{alumno}/edit', [AlumnoPreceptorController::class, 'editAlumno'])->name('alumnos.edit');
  Route::put('alumnos/{alumno}', [AlumnoPreceptorController::class, 'update'])->name('alumnos.update');
  Route::post('/preceptor/alumno/{alumno}/rematricular', [RematriculacionController::class, 'rematricular'])
   ->name('alumno.rematricular');
  Route::post('preceptor/alumnos', [AlumnoPreceptorController::class, 'store'])->name('alumnos.store');
  Route::get('preceptor/alumnos/{alumno}/verificar', [AlumnoPreceptorController::class, 'verificar'])
   ->name('alumnos.verificar');

  // Rutas inscripciones
  Route::get('preceptor/inscriptos/create', [InscripcionPreceptorController::class, 'create'])->name('inscriptos.create');


  // Rutas cursadas
  Route::get('/cursadas', [CursadasPreceptorController::class, 'index'])->name('cursadas.index');
  Route::get('/cursadas/{cursada}/edit', [CursadasPreceptorController::class, 'edit'])->name('cursadas.edit');
  Route::get('/cursadas/create', [CursadasPreceptorController::class, 'create'])->name('cursadas.create');
  Route::post('/cursadas', [CursadasPreceptorController::class, 'store'])->name('cursadas.store');
  // Rutas mesas
  Route::get('/mesas', [PreceptorController::class, 'mesas'])->name('mesas.index');

  //rutas Examenes
  Route::get('preceptor/examenes/{examen}/edit', [ExamenPreceptorController::class, 'edit'])->name('examenes.edit');
 });
