<?php

use App\Http\Controllers\preceptor\AlumnoPreceptorController;
use App\Http\Controllers\Admin\AlumnoCrudController;

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
  Route::post('/preceptor/alumno/{alumno}/rematricular', [AlumnoPreceptorController::class, 'rematricular'])
   ->name('preceptor.alumno.rematricular');

  // Rutas cursadas
  Route::get('/cursadas', [PreceptorController::class, 'cursadas'])->name('cursadas.index');

  // Rutas mesas
  Route::get('/mesas', [PreceptorController::class, 'mesas'])->name('mesas.index');
 });
