<?php
use App\Http\Controllers\Preceptor\InscripcionPreceptorController;
use App\Http\Controllers\Preceptor\CursadasPreceptorController;
use App\Http\Controllers\preceptor\AlumnoPreceptorController;
use App\Http\Controllers\Admin\AlumnoCrudController;
use App\htpp\Controllers\RematriculacionController;
use App\Http\Controllers\Preceptor\ExamenPreceptorController;
use App\Http\Controllers\Preceptor\mesaPreceptorController;
use App\Http\Controllers\Admin\AdminPdfController;

Route::prefix('preceptor')
 ->middleware(['auth:admin'])
 ->name('preceptor.')
 ->group(function () {
  Route::get('/dashboard', [AlumnoPreceptorController::class, 'dashboard'])->name('dashboard');

  // Rutas alumnos
  Route::get('/alumnos', [AlumnoPreceptorController::class, 'index'])->name('alumnos.index');
  Route::get('/alumnos/create', [AlumnoPreceptorController::class, 'create'])->name('alumnos.create');
  Route::get('/alumnos/{alumno}/edit', [AlumnoPreceptorController::class, 'edit'])->name('alumnos.edit');
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
  Route::get('/mesas', [mesaPreceptorController::class, 'index'])->name('mesas.index');
  Route::get('/mesas/create', [mesaPreceptorController::class, 'create'])->name('mesas.create');
  Route::post('/mesas', [mesaPreceptorController::class, 'store'])->name('mesas.store');
  Route::get('/mesas/{mesa}/edit', [mesaPreceptorController::class, 'edit'])->name('mesas.edit');
  Route::put('/mesas/{mesa}', [mesaPreceptorController::class, 'update'])->name('mesas.update');
  Route::delete('/mesas/{mesa}', [mesaPreceptorController::class, 'destroy'])->name('mesas.destroy');
  Route::get('/mesas/acta-volante/{mesa}', [AdminPdfController::class, 'acta_volante'])->name('mesas.acta');
  Route::get('/mesas/acta-volante-prom/{mesa}', [AdminPdfController::class, 'actaVolantePromocion'])->name('mesas.actaprom');
  Route::get('/mesas/acta-volante-libre/{mesa}', [AdminPdfController::class, 'actaVolanteLibre'])->name('mesas.actalibre');

  //rutas Examenes
  Route::get('preceptor/examenes/{examen}/edit', [ExamenPreceptorController::class, 'edit'])->name('examenes.edit');
  Route::post('preceptor/examenes', [ExamenPreceptorController::class, 'store'])->name('examenes.store');
 });
