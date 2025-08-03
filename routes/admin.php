<?php

use App\Http\Controllers\Admin\AdminCopiaDB;
use App\Http\Controllers\PreceptorController;
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

Route::redirect('/Regente', '/Regente/login');

// Rutas del administrador
Route::middleware(['web'])->prefix('admin')->group(function () {

    // ========================
    // Rutas de Autenticación
    // ========================
    Route::get('login', [AdminAuthController::class, 'loginView'])->name('Regente.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('Regente.login.post');
    Route::get('logout', [AdminAuthController::class, 'logout'])->name('Regente.logout');

    // ========================
    // Rutas para Actas y Certificados
    // ========================
    Route::get('/mesas/acta-volante/{mesa}', [AdminPdfController::class, 'acta_volante'])->name('Regente.mesas.acta');
    Route::get('/mesas/acta-volante-prom/{mesa}', [AdminPdfController::class, 'actaVolantePromocion'])->name('Regente.mesas.actaprom');
    Route::get('/mesas/acta-volante-libre/{mesa}', [AdminPdfController::class, 'actaVolanteLibre'])->name('Regente.mesas.actalibre');

    Route::get('/alumnos/regular/{alumno}', [AdminPdfController::class, 'constanciaRegular'])->name('Regente.alumnos.regular');
    Route::get('/Regente/alumnos/{alumno}/analitico-pdf', [AdminPdfController::class, 'analitico'])->name('Regente.alumnos.analitico.pdf');

    // ========================
    // Controladores de recursos para operaciones CRUD
    // ========================
    Route::resource('alumnos', AlumnoCrudController::class, ['as' => 'admin'])->middleware('auth:admin')
        ->missing(function () {
            return redirect()->route('regente.alumnos.index')->with('aviso', 'El alumno no existe o ha sido eliminado');
        })->except('show');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/preceptor/alumnos/index', [PreceptorController::class, 'index'])->name('preceptor.alumnos.index');
    });

    Route::resource('inscriptos', EgresadosAdminController::class, ['as' => 'admin'])->missing(function () {
        return redirect()->route('Regente.inscriptos.index')->with('aviso', 'La inscripción no existe o ha sido eliminada');
    })->except('show');

    // Rutas de Profesores
    Route::resource('profesores', ProfesoresCrudController::class, [
        'as' => 'Regente',
        'parameters' => ['profesores' => 'profesor']
    ])->except('show')->missing(function () {
        return redirect()->route('Regente.profesores.index')->with('aviso', 'El profesor no existe o ha sido eliminado');
    });

    // Rutas de Carreras
    Route::resource('carreras', CarrerasCrudController::class, ['as' => 'admin'])->middleware('auth:admin')->missing(function () {
        return redirect()->route('Regente.carreras.index')->with('aviso', 'La carrera no existe o ha sido eliminada');
    })->except('show');

    Route::post('carreras/add_asignatura', [CarrerasCrudController::class, 'addAsignatura'])->name('Regente.carreras.addAsignatura');
    Route::get('carreras/add_asignatura/{carrera}', [CarrerasCrudController::class, 'addAsignaturaView'])->name('Regente.carreras.addAsignaturaView');
    Route::get('carreras/create_asignatura/{carrera}', [CarrerasCrudController::class, 'createAsignaturaView'])->name('Regente.carreras.createAsignaturaView');
    Route::post('carreras/create_asignatura/{carrera}', [CarrerasCrudController::class, 'createAsignatura'])->name('Regente.carreras.createAsignatura');

    // Rutas de Asignaturas
    Route::resource('asignaturas', AsignaturasCrudController::class, ['as' => 'admin'])->missing(function () {
        return redirect()->route('Regente.asignaturas.index')->with('aviso', 'La asignatura no existe o ha sido eliminada');
    })->except('show');

    // Rutas de Cursadas
    Route::get('cursadas', [CursadasAdminController::class, 'index'])->name('Regente.cursadas.index');
    Route::get('cursadas/{cursada}/edit', [CursadasAdminController::class, 'edit'])->name('Regente.cursadas.edit');
    Route::put('cursadas/{cursada}/edit', [CursadasAdminController::class, 'update'])->name('Regente.cursadas.update');
    Route::delete('cursadas/{cursada}', [CursadasAdminController::class, 'delete'])->name('Regente.cursadas.destroy');
    Route::get('cursadas/create', [CursadasAdminController::class, 'create'])->name('Regente.cursadas.create');
    Route::post('cursadas/create', [CursadasAdminController::class, 'store'])->name('Regente.cursadas.store');

    // Rutas de Exámenes
    Route::resource('examenes', ExamenesCrudController::class, [
        'as' => 'admin',
        'parameters' => ['examenes' => 'examen']
    ])->only('store', 'edit', 'update', 'destroy');
    Route::post('examenes/{examen}/nota', [ExamenesCrudController::class, 'modificarNota'])->name('Regente.examenes.nota');

    // Rutas de Seguridad
    Route::get('seguridad', [AdminSeguridadController::class, 'vista'])->name('Regente.seguridad.index');
    Route::post('seguridad', [AdminSeguridadController::class, 'editar'])->name('Regente.seguridad.update');

    // Rutas de Configuración
    Route::get('config', [ConfigController::class, 'index'])->name('Regente.config.index');
    Route::post('config', [ConfigController::class, 'setear'])->name('Regente.config.set');
    Route::post('config/one', [ConfigController::class, 'setOnly'])->name('Regente.config.setone');
    Route::get('config/modoseguro', [ConfigController::class, 'modoSeguro'])->name('Regente.config.modoseguro');

    // Rutas de Correlativas
    Route::post('correlativa/{asignatura}', [AdminCorrelativasController::class, 'agregar'])->name('correlativa.agregar');
    Route::delete('correlativa/{asignatura}', [AdminCorrelativasController::class, 'eliminar'])->name('correlativa.eliminar');

    // Rutas de Días Hábiles
    Route::get('dias-habiles', [AdminDiasHabilesController::class, 'index'])->name('Regente.habiles.index');
    Route::post('dias-habiles', [AdminDiasHabilesController::class, 'store'])->name('Regente.habiles.store');
    Route::delete('dias-habiles/{habil}', [AdminDiasHabilesController::class, 'destroy'])->name('Regente.habiles.destroy');

    // Rutas de Matrícula
    Route::get('matricular/{alumno}', [AdminMatriculacionController::class, 'rematriculacion_vista'])->name('Regente.alumno.rematricular');
    Route::post('matricular/{alumno}/{carrera}', [AdminMatriculacionController::class, 'rematriculacion'])->name('Regente.alumno.matricular.post');

    // Rutas de Exportación a Excel
    Route::get('cursantes/carrera/{carrera}', [AdminExportController::class, 'cursadasCarrera'])->name('excel.cursantes.carrera');
    Route::get('cursantes/plan/{plan}', [AdminExportController::class, 'cursadasPlan'])->name('excel.cursantes.plan');
});
