<?php

use App\Http\Controllers\Admin\AdminCopiaDB;
use App\Http\Controllers\preceptor\AlumnoPreceptorController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCorrelativasController;
use App\Http\Controllers\Admin\AdminCursadasLotes;
use App\Http\Controllers\Admin\AdminDiasHabilesController;
use App\Http\Controllers\Admin\AdminExcelController;
use App\Http\Controllers\Admin\AdminExportController;
use App\Http\Controllers\Admin\AdminMatriculacionController;
use App\Http\Controllers\Admin\AdminMesaPorCarreraController;
use App\Http\Controllers\Admin\AdminMesasLotes;
use App\Http\Controllers\Admin\AdminPdfController;
use App\Http\Controllers\Admin\AlumnoCrudController;
use App\Http\Controllers\Admin\AsignaturasCrudController;
use App\Http\Controllers\Admin\CarrerasCrudController;
use App\Http\Controllers\Admin\MesasCrudController;
use App\Http\Controllers\Admin\ProfesoresCrudController;
use App\Http\Controllers\Admin\AdminsCrudController;
use App\Http\Controllers\Admin\AdminSeguridadController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\ExamenesCrudController;
use App\Http\Controllers\Admin\CursadasAdminController;
use App\Http\Controllers\Admin\EgresadosAdminController;
use App\Http\Controllers\PdfsController;
use App\Http\Controllers\AlumnoController;
use App\Models\Alumno;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Mensaje;
use App\Models\Mesa;
use App\Models\Profesor;
use App\Services\TextFormatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\AsignaturaController;

Route::redirect('/admin', '/admin/login');

Route::middleware(['web'])->prefix('admin')->group(function () {

    // -----------------------------
    // LOGIN / LOGOUT
    // -----------------------------
    Route::get('login', [AdminAuthController::class, 'loginView'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::get('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // -----------------------------
    // ALUMNOS
    // -----------------------------
    Route::get('alumnos/verificar/{alumno}', [AlumnoCrudController::class, 'verificar'])
        ->name('admin.alumnos.verificar')->middleware('auth:admin');

    Route::resource('alumnos', AlumnoCrudController::class, ['as' => 'admin'])
        ->middleware('auth:admin')
        ->missing(function () {
            return redirect()->route('admin.alumnos.index')->with('aviso', 'El alumno no existe o ha sido eliminado');
        })->except('show');

    Route::get('/admin/alumnos/{alumno}/analitico-pdf', [AdminPdfController::class, 'analitico'])
        ->name('admin.alumnos.analitico.pdf');

    Route::get('/alumnos/regular/{alumno}', [AdminPdfController::class, 'constanciaRegular'])
        ->name('admin.alumnos.regular');

    Route::get('matricular/{alumno}', [AdminMatriculacionController::class, 'rematriculacion_vista'])
        ->name('admin.alumno.rematricular');
    Route::post('matricular/{alumno}/{carrera}', [AdminMatriculacionController::class, 'rematriculacion'])
        ->name('admin.alumno.matricular.post');

    // -----------------------------
    // PRECEPTOR
    // -----------------------------
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/preceptor/alumnos/index', [AlumnoPreceptorController::class, 'index'])
            ->name('preceptor.alumnos.index');
    });

    // -----------------------------
    // EGRESADOS
    // -----------------------------
    Route::resource('inscriptos', EgresadosAdminController::class, ['as' => 'admin'])
        ->missing(function () {
            return redirect()->route('admin.inscriptos.index')->with('aviso', 'La inscripcion no existe o ha sido eliminada');
        })->except('show');

    // -----------------------------
    // PROFESORES
    // -----------------------------
    Route::resource('profesores', ProfesoresCrudController::class, [
        'as' => 'admin',
        'parameters' => ['profesores' => 'profesor']
    ])->except('show')->missing(function () {
        return redirect()->route('admin.profesores.index')->with('aviso', 'El profesor no existe o ha sido eliminado');
    });

    // -----------------------------
    // CARRERAS
    // -----------------------------
    Route::resource('carreras', CarrerasCrudController::class, ['as' => 'admin'])
        ->middleware('auth:admin')
        ->missing(function () {
            return redirect()->route('admin.carreras.index')->with('aviso', 'La carrera no existe o ha sido eliminada');
        })->except('show');

    Route::post('carreras/add_asignatura', [CarrerasCrudController::class, 'addAsignatura'])
        ->name('admin.carreras.addAsignatura');
    Route::get('carreras/add_asignatura/{carrera}', [CarrerasCrudController::class, 'addAsignaturaView'])
        ->name('admin.carreras.addAsignaturaView');

    Route::get('carreras/create_asignatura/{carrera}', [CarrerasCrudController::class, 'createAsignaturaView'])
        ->name('admin.carreras.createAsignaturaView');
    Route::post('carreras/create_asignatura/{carrera}', [CarrerasCrudController::class, 'createAsignatura'])
        ->name('admin.carreras.createAsignatura');

    Route::delete('carreras/{carrera}', [CarrerasCrudController::class, 'destroy'])->name('admin.carreras.destroy');
    Route::post('carreras/{carrera}/desactivar', [CarrerasCrudController::class, 'desactivar'])->name('admin.carreras.desactivar');
    Route::post('carreras/{carrera}/reactivar', [CarrerasCrudController::class, 'reactivar'])->name('admin.carreras.reactivar');

    Route::get('carreras/resolucion/{carrera}', function (Request $request, Carrera $carrera) {
        return Storage::download($carrera->resolucion_archivo);
    })->name('admin.carreras.resolucion');

    Route::get('carreras/resolucion-delete/{carrera}', function (Request $request, Carrera $carrera) {
        Storage::delete($carrera->resolucion_archivo);
        $carrera->resolucion_archivo = '';
        $carrera->save();
        return redirect()->back();
    })->name('admin.carreras.resolucion.borrar');

    // -----------------------------
    // ASIGNATURAS
    // -----------------------------
    Route::resource('asignaturas', AsignaturasCrudController::class, ['as' => 'admin'])
        ->missing(function () {
            return redirect()->route('admin.asignaturas.index')->with('aviso', 'La asignatura no existe o ha sido eliminada');
        })->except('show');

    Route::get('/asignaturas', [AsignaturasCrudController::class, 'index'])->name('admin.asignaturas.index');
    Route::get('/asignaturas/create', [AsignaturasCrudController::class, 'create'])->name('admin.asignaturas.create');
    Route::get('/asignaturas/{asignatura}/edit', [AsignaturasCrudController::class, 'edit'])->name('admin.asignaturas.edit');
    Route::post('/asignaturas', [AsignaturasCrudController::class, 'store'])->name('admin.asignaturas.store');
    Route::put('/asignaturas/{asignatura}', [AsignaturasCrudController::class, 'update'])->name('admin.asignaturas.update');
    Route::delete('/asignaturas/{asignatura}', [AsignaturasCrudController::class, 'destroy'])->name('admin.asignaturas.destroy');
Route::post('/admin/asignaturas/{asignatura}/desvincular/{carrera}', 
    [AsignaturasCrudController::class,'Desvincular'])
    ->name('admin.asignaturas.desvincular');




    // -----------------------------
    // CURSADAS
    // -----------------------------
    Route::get('cursadas', [CursadasAdminController::class, 'index'])->name('admin.cursadas.index');
    Route::get('cursadas/create', [CursadasAdminController::class, 'create'])->name('admin.cursadas.create');
    Route::post('cursadas/create', [CursadasAdminController::class, 'store'])->name('admin.cursadas.store');
    Route::get('cursadas/{cursada}/edit', [CursadasAdminController::class, 'edit'])->name('admin.cursadas.edit');
    Route::put('cursadas/{cursada}/edit', [CursadasAdminController::class, 'update'])->name('admin.cursadas.update');
    Route::delete('cursadas/{cursada}', [CursadasAdminController::class, 'delete'])->name('admin.cursadas.destroy');

    Route::get('cursadas/{asignatura}', [AdminCursadasLotes::class, 'vista'])->name('admin.cursadas.masivo');
    Route::post('masivo/cursadas', [AdminCursadasLotes::class, 'cargar'])->name('admin.cursadas.masivo.post');

    // -----------------------------
    // MESAS / EXAMENES
    // -----------------------------
    Route::resource('mesas', MesasCrudController::class, ['as' => 'admin'])->middleware('auth:admin')->except('show');

    Route::get('mesas-dual/{carrera}/{asignatura}', [AdminMesasLotes::class, 'vista'])->name('admin.mesas.dual');
    Route::post('mesas-dual/{carrera}/{asignatura}', [AdminMesasLotes::class, 'store'])->name('admin.mesas.dualpost');

    Route::get('/mesas/acta-volante/{mesa}', [AdminPdfController::class, 'acta_volante'])->name('admin.mesas.acta');
    Route::get('/mesas/acta-volante-prom/{mesa}', [AdminPdfController::class, 'actaVolantePromocion'])->name('admin.mesas.actaprom');
    Route::get('/mesas/acta-volante-libre/{mesa}', [AdminPdfController::class, 'actaVolanteLibre'])->name('admin.mesas.actalibre');

    Route::resource('examenes', ExamenesCrudController::class, [
        'as' => 'admin',
        'parameters' => ['examenes' => 'examen']
    ])->only('store', 'edit', 'update', 'destroy');
    Route::post('examenes/{examen}/nota', [ExamenesCrudController::class, 'modificarNota'])->name('admin.examenes.nota');

    // -----------------------------
    // ADMINS
    // -----------------------------
    Route::resource('admins', AdminsCrudController::class, ['as' => 'admin'])->except('show');

    // -----------------------------
    // CONFIGURACION
    // -----------------------------
    Route::get('config', [ConfigController::class, 'index'])->name('admin.config.index');
    Route::post('config', [ConfigController::class, 'setear'])->name('admin.config.set');
    Route::post('config/one', [ConfigController::class, 'setOnly'])->name('admin.config.setone');
    Route::get('config/modoseguro', [ConfigController::class, 'modoSeguro'])->name('admin.config.modoseguro');

    // -----------------------------
    // CORRELATIVAS
    // -----------------------------
    Route::post('correlativa/{asignatura}', [AdminCorrelativasController::class, 'agregar'])->name('correlativa.agregar');
    Route::delete('correlativa/{asignatura}', [AdminCorrelativasController::class, 'eliminar'])->name('correlativa.eliminar');

    // -----------------------------
    // DIAS HABILES
    // -----------------------------
    Route::get('dias-habiles', [AdminDiasHabilesController::class, 'index'])->name('admin.habiles.index');
    Route::post('dias-habiles', [AdminDiasHabilesController::class, 'store'])->name('admin.habiles.store');
    Route::delete('dias-habiles/{habil}', [AdminDiasHabilesController::class, 'destroy'])->name('admin.habiles.destroy');

    // -----------------------------
    // EXPORTS
    // -----------------------------
    Route::get('cursantes/carrera/{carrera}', [AdminExportController::class, 'cursadasCarrera'])->name('excel.cursadas.carrera');
    Route::get('cursantes/{asignatura}', [AdminExportController::class, 'cursadasAsignatura'])->name('excel.cursadas.asig');

    // -----------------------------
    // SEGURIDAD
    // -----------------------------
    Route::get('seguridad', [AdminSeguridadController::class, 'vista'])->name('admin.seguridad.index');
    Route::post('seguridad', [AdminSeguridadController::class, 'editar'])->name('admin.seguridad.update');

    // -----------------------------
    // NORMALIZACION DE DATOS
    // -----------------------------
    Route::get('normalizar', function () {
        foreach (Alumno::all() as $alumno) {
            $alumno->nombre = TextFormatService::ucwords($alumno->nombre);
            $alumno->apellido = TextFormatService::ucwords($alumno->apellido);
            $alumno->ciudad = TextFormatService::ucfirst($alumno->ciudad);
            $alumno->calle = TextFormatService::ucfirst($alumno->calle);
            $alumno->email = strtolower($alumno->email);
            $alumno->save();
        }

        foreach (Profesor::all() as $profe) {
            $profe->nombre = TextFormatService::ucwords($profe->nombre);
            $profe->apellido = TextFormatService::ucwords($profe->apellido);
            $profe->ciudad = TextFormatService::ucfirst($profe->ciudad);
            $profe->calle = TextFormatService::ucfirst($profe->calle);
            $profe->observaciones = TextFormatService::ucfirst($profe->observaciones);
            $profe->email = strtolower($profe->email);
            $profe->formacion_academica = TextFormatService::ucfirst($profe->formacion_academica);
            $profe->save();
        }

        foreach (Carrera::all() as $carrera) {
            $carrera->nombre = TextFormatService::ucfirst($carrera->nombre);
            $carrera->observaciones = TextFormatService::ucfirst($carrera->observaciones);
            $carrera->save();
        }

        foreach (Asignatura::all() as $asignatura) {
            $asignatura->observaciones = TextFormatService::ucfirst($asignatura->observaciones);
            $asignatura->nombre = TextFormatService::ucfirst($asignatura->nombre);
            $asignatura->save();
        }

        return redirect()->back()->with('mensaje', 'Se han normalizado los datos');
    });

    // -----------------------------
    // COPIA DE SEGURIDAD / RESTAURACION
    // -----------------------------
    Route::get('copia', [AdminCopiaDB::class, 'crearCopia']);
    Route::get('restaurar', [AdminCopiaDB::class, 'restaurarCopia']);
});
