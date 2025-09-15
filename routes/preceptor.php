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
use App\Http\Controllers\Preceptor\PreceptorCursadasLotes;
use App\Http\Controllers\Preceptor\PreceptorMesasLotes;

Route::prefix('preceptor')
 ->middleware(['auth:admin'])
 ->name('preceptor.')
 ->group(function () {

  // Alumnos
Route::get('alumnos/verificar/{alumno}', [AlumnoPreceptorController::class, 'verificar'])
        ->name('alumnos.verificar')->middleware('auth:admin');

Route::resource('alumnos', AlumnoPreceptorController::class)
    ->middleware('auth:admin')
    ->missing(function () {
        return redirect()->route('preceptor.alumnos.index')
            ->with('aviso', 'El alumno no existe o ha sido eliminado');
    })->except('show');



    Route::get('alumnos/{alumno}/analitico-pdf', [AdminPdfController::class, 'analitico'])
        ->name('admin.alumnos.analitico.pdf');

    Route::get('/alumnos/regular/{alumno}', [AdminPdfController::class, 'constanciaRegular'])
        ->name('admin.alumnos.regular');

    Route::get('matricular/{alumno}', [AdminMatriculacionController::class, 'rematriculacion_vista'])
        ->name('alumno.rematricular');
    Route::post('matricular/{alumno}/{carrera}', [AdminMatriculacionController::class, 'rematriculacion'])
        ->name('alumno.matricular.post');

  // Inscriptos
  Route::get('/inscriptos/create', [InscripcionPreceptorController::class, 'create'])->name('inscriptos.create');

  // Cursadas
   Route::get('cursadas', [CursadasPreceptorController::class, 'index'])->name('cursadas.index');
    Route::get('cursadas/create', [CursadasPreceptorController::class, 'create'])->name('cursadas.create');
    Route::post('cursadas/create', [CursadasPreceptorController::class, 'store'])->name('cursadas.store');
    Route::get('cursadas/{cursada}/edit', [CursadasPreceptorController::class, 'edit'])->name('cursadas.edit');
    Route::put('cursadas/{cursada}/edit', [CursadasPreceptorController::class, 'update'])->name('cursadas.update');
    Route::delete('cursadas/{cursada}', [CursadasPreceptorController::class, 'destroy'])->name('cursadas.destroy');

    Route::get('cursadas/masivo/{asignatura}', [PreceptorCursadasLotes::class, 'vista'])
    ->name('cursadas.masivo');

    Route::post('masivo/cursadas', [PreceptorCursadasLotes::class, 'cargar'])->name('cursadas.masivo.post');

    // mesas y examen
 Route::resource('mesas', MesaPreceptorController::class)->middleware('auth:admin')->except('show');

    Route::get('mesas-dual/{carrera}/{asignatura}', [PreceptorMesasLotes::class, 'vista'])->name('mesas.dual');
    Route::post('mesas-dual/{carrera}/{asignatura}', [PreceptorMesasLotes::class, 'store'])->name('mesas.dualpost');

    Route::get('/mesas/acta-volante/{mesa}', [AdminPdfController::class, 'acta_volante'])->name('mesas.acta');
    Route::get('/mesas/acta-volante-prom/{mesa}', [AdminPdfController::class, 'actaVolantePromocion'])->name('mesas.actaprom');
    Route::get('/mesas/acta-volante-libre/{mesa}', [AdminPdfController::class, 'actaVolanteLibre'])->name('mesas.actalibre');

    Route::resource('examenes', ExamenPreceptorController::class, [
        'parameters' => ['examenes' => 'examen']
    ])->only('store', 'edit', 'update', 'destroy');
    Route::post('examenes/{examen}/nota', [ExamenPreceptorController::class, 'modificarNota'])->name('examenes.nota');


  // Actas PDF
  Route::get('/mesas/acta-volante/{mesa}', [AdminPdfController::class, 'acta_volante'])->name('mesas.acta');
  Route::get('/mesas/acta-volante-prom/{mesa}', [AdminPdfController::class, 'actaVolantePromocion'])->name('mesas.actaprom');
  Route::get('/mesas/acta-volante-libre/{mesa}', [AdminPdfController::class, 'actaVolanteLibre'])->name('mesas.actalibre');

  // Asignaturas
  Route::resource('asignaturas', AsignaturasPreceptorController::class)
        ->missing(function () {
            return redirect()->route('asignaturas.index')->with('aviso', 'La asignatura no existe o ha sido eliminada');
        })->except('show');

    Route::get('/asignaturas', [AsignaturasPreceptorController::class, 'index'])->name('asignaturas.index');
    Route::get('/asignaturas/create', [AsignaturasPreceptorController::class, 'create'])->name('asignaturas.create');
    Route::get('/asignaturas/{asignatura}/edit', [AsignaturasPreceptorController::class, 'edit'])->name('asignaturas.edit');
    Route::post('/asignaturas', [AsignaturasPreceptorController::class, 'store'])->name('asignaturas.store');
    Route::put('/asignaturas/{asignatura}', [AsignaturasPreceptorController::class, 'update'])->name('asignaturas.update');
    Route::delete('/asignaturas/{asignatura}', [AsignaturasPreceptorController::class, 'destroy'])->name('asignaturas.destroy');
Route::post('/asignaturas/{asignatura}/desvincular/{carrera}', 
    [AsignaturasPreceptorController::class,'Desvincular'])
    ->name('asignaturas.desvincular');

  // Carreras
  Route::resource('carreras', CarrerasPreceptorController::class)
        ->middleware('auth:admin')
        ->missing(function () {
            return redirect()->route('carreras.index')->with('aviso', 'La carrera no existe o ha sido eliminada');
        })->except('show');

    Route::post('carreras/add_asignatura', [CarrerasPreceptorController::class, 'addAsignatura'])
        ->name('carreras.addAsignatura');
    Route::get('carreras/add_asignatura/{carrera}', [CarrerasPreceptorController::class, 'addAsignaturaView'])
        ->name('carreras.addAsignaturaView');

    Route::get('carreras/create_asignatura/{carrera}', [CarrerasPreceptorController::class, 'createAsignaturaView'])
        ->name('carreras.createAsignaturaView');
    Route::post('carreras/create_asignatura/{carrera}', [CarrerasPreceptorController::class, 'createAsignatura'])
        ->name('carreras.createAsignatura');

    Route::delete('carreras/{carrera}', [CarrerasPreceptorController::class, 'destroy'])->name('carreras.destroy');
    Route::post('carreras/{carrera}/desactivar', [CarrerasPreceptorController::class, 'desactivar'])->name('carreras.desactivar');
    Route::post('carreras/{carrera}/reactivar', [CarrerasPreceptorController::class, 'reactivar'])->name('carreras.reactivar');

    Route::get('carreras/resolucion/{carrera}', function (Request $request, Carrera $carrera) {
        return Storage::download($carrera->resolucion_archivo);
    })->name('carreras.resolucion');

    Route::get('carreras/resolucion-delete/{carrera}', function (Request $request, Carrera $carrera) {
        Storage::delete($carrera->resolucion_archivo);
        $carrera->resolucion_archivo = '';
        $carrera->save();
        return redirect()->back();
    })->name('carreras.resolucion.borrar');


    //inscriptos
    Route::resource('inscriptos', InscripcionPreceptorController::class,)
        ->missing(function () {
            return redirect()->route('preceptor.inscriptos.index')->with('aviso', 'La inscripcion no existe o ha sido eliminada');
        })->except('show');
 });
