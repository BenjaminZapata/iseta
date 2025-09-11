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
use App\Http\Controllers\Admin\AdminMatriculacionController;

Route::prefix('preceptor')
 ->middleware(['auth:admin'])
 ->name('preceptor.')
 ->group(function () {

  // Alumnos
Route::get('alumnos/verificar/{alumno}', [AlumnoPreceptorController::class, 'verificar'])
        ->name('preceptor.alumnos.verificar')->middleware('auth:admin');

    Route::resource('alumnos', AlumnoPreceptorController::class, ['as' => 'admin'])
        ->middleware('auth:admin')
        ->missing(function () {
            return redirect()->route('preceptor.alumnos.index')->with('aviso', 'El alumno no existe o ha sido eliminado');
        })->except('show');

    Route::get('/admin/alumnos/{alumno}/analitico-pdf', [AdminPdfController::class, 'analitico'])
        ->name('admin.alumnos.analitico.pdf');

    Route::get('/alumnos/regular/{alumno}', [AdminPdfController::class, 'constanciaRegular'])
        ->name('admin.alumnos.regular');

    Route::get('matricular/{alumno}', [AdminMatriculacionController::class, 'rematriculacion_vista'])
        ->name('preceptor.alumno.rematricular');
    Route::post('matricular/{alumno}/{carrera}', [AdminMatriculacionController::class, 'rematriculacion'])
        ->name('preceptor.alumno.matricular.post');

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
  Route::resource('asignaturas', AsignaturasPreceptorController::class, ['as' => 'admin'])
        ->missing(function () {
            return redirect()->route('preceptor.asignaturas.index')->with('aviso', 'La asignatura no existe o ha sido eliminada');
        })->except('show');

    Route::get('/asignaturas', [AsignaturasPreceptorController::class, 'index'])->name('preceptor.asignaturas.index');
    Route::get('/asignaturas/create', [AsignaturasPreceptorController::class, 'create'])->name('preceptor.asignaturas.create');
    Route::get('/asignaturas/{asignatura}/edit', [AsignaturasPreceptorController::class, 'edit'])->name('preceptor.asignaturas.edit');
    Route::post('/asignaturas', [AsignaturasPreceptorController::class, 'store'])->name('preceptor.asignaturas.store');
    Route::put('/asignaturas/{asignatura}', [AsignaturasPreceptorController::class, 'update'])->name('preceptor.asignaturas.update');
    Route::delete('/asignaturas/{asignatura}', [AsignaturasPreceptorController::class, 'destroy'])->name('preceptor.asignaturas.destroy');
Route::post('/asignaturas/{asignatura}/desvincular/{carrera}', 
    [AsignaturasPreceptorController::class,'Desvincular'])
    ->name('preceptor.asignaturas.desvincular');

  // Carreras
  Route::resource('carreras', CarrerasPreceptorController::class, ['as' => 'admin'])
        ->middleware('auth:admin')
        ->missing(function () {
            return redirect()->route('preceptor.carreras.index')->with('aviso', 'La carrera no existe o ha sido eliminada');
        })->except('show');

    Route::post('carreras/add_asignatura', [CarrerasPreceptorController::class, 'addAsignatura'])
        ->name('preceptor.carreras.addAsignatura');
    Route::get('carreras/add_asignatura/{carrera}', [CarrerasPreceptorController::class, 'addAsignaturaView'])
        ->name('preceptor.carreras.addAsignaturaView');

    Route::get('carreras/create_asignatura/{carrera}', [CarrerasPreceptorController::class, 'createAsignaturaView'])
        ->name('preceptor.carreras.createAsignaturaView');
    Route::post('carreras/create_asignatura/{carrera}', [CarrerasPreceptorController::class, 'createAsignatura'])
        ->name('preceptor.carreras.createAsignatura');

    Route::delete('carreras/{carrera}', [CarrerasPreceptorController::class, 'destroy'])->name('preceptor.carreras.destroy');
    Route::post('carreras/{carrera}/desactivar', [CarrerasPreceptorController::class, 'desactivar'])->name('preceptor.carreras.desactivar');
    Route::post('carreras/{carrera}/reactivar', [CarrerasPreceptorController::class, 'reactivar'])->name('preceptor.carreras.reactivar');

    Route::get('carreras/resolucion/{carrera}', function (Request $request, Carrera $carrera) {
        return Storage::download($carrera->resolucion_archivo);
    })->name('admin.carreras.resolucion');

    Route::get('carreras/resolucion-delete/{carrera}', function (Request $request, Carrera $carrera) {
        Storage::delete($carrera->resolucion_archivo);
        $carrera->resolucion_archivo = '';
        $carrera->save();
        return redirect()->back();
    })->name('preceptor.carreras.resolucion.borrar');
 });
