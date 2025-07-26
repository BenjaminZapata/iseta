<?php

use App\Http\Controllers\PreceptorController;
use App\Http\Controllers\Admin\AlumnoCrudController;

Route::prefix('preceptor')
 ->middleware(['auth:admin'])
 ->name('preceptor.')
 ->group(function () {
  Route::get('/dashboard', [PreceptorController::class, 'dashboard'])->name('dashboard');
  // rutas de preceptor/alumnos
  Route::get('/alumnos', [PreceptorController::class, 'alumnos'])->name('alumnos.index');
  Route::get('/alumnos/create', [PreceptorController::class, 'crearAlumno'])->name('alumnos.create');
  Route::get('preceptor/alumnos/{alumno}/edit', [PreceptorController::class, 'editAlumno'])
   ->name('preceptor.alumnos.edit');
  //rutas de preceptor/cursadas
  Route::get('/cursadas', [PreceptorController::class, 'cursadas'])->name('cursadas.index');

  //rutas de preceptor/mesas
  Route::get('/mesas', [PreceptorController::class, 'mesas'])->name('mesas.index');
 });