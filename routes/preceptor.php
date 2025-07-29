<?php

use App\Http\Controllers\PreceptorController;
use App\Http\Controllers\Admin\AlumnoCrudController;

Route::prefix('preceptor')
 ->middleware(['auth:admin'])
 ->name('preceptor.')
 ->group(function () {
  Route::get('/dashboard', [PreceptorController::class, 'dashboard'])->name('dashboard');

  // Rutas alumnos
  Route::get('/alumnos', [PreceptorController::class, 'alumnos'])->name('alumnos.index');
  Route::get('/alumnos/create', [PreceptorController::class, 'crearAlumno'])->name('alumnos.create');
  Route::get('/alumnos/{alumno}/edit', [PreceptorController::class, 'editAlumno'])->name('alumnos.edit');

  // Rutas cursadas
  Route::get('/cursadas', [PreceptorController::class, 'cursadas'])->name('cursadas.index');

  // Rutas mesas
  Route::get('/mesas', [PreceptorController::class, 'mesas'])->name('mesas.index');
 });
