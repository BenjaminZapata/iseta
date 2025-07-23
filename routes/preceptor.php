<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PreceptorController;

Route::prefix('preceptor')
 ->middleware(['auth:admin']) // Podés agregar 'check.rol:preceptor' si lo tenés
 ->name('preceptor.')
 ->group(function () {
  Route::get('/dashboard', function () {
   return view('Admin.Preceptor.dashboard');
  })->name('dashboard');

  Route::get('/alumnos', [PreceptorController::class, 'alumnos'])->name('alumnos');
  Route::get('/cursadas', [PreceptorController::class, 'cursadas'])->name('cursadas');
  Route::get('/mesas', [PreceptorController::class, 'mesas'])->name('mesas');
 });
