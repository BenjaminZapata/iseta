<?php

use App\Http\Controllers\PreceptorController;

Route::prefix('preceptor')
 ->middleware(['auth:admin'])
 ->name('preceptor.')
 ->group(function () {
  Route::get('/dashboard', [PreceptorController::class, 'dashboard'])->name('dashboard');
  Route::get('/alumnos', [PreceptorController::class, 'alumnos'])->name('alumnos.index');
  Route::get('/cursadas', [PreceptorController::class, 'cursadas'])->name('cursadas.index');
  Route::get('/mesas', [PreceptorController::class, 'mesas'])->name('mesas.index');
 });