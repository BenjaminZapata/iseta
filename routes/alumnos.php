<?php

use App\Http\Controllers\AlumnoAuthController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\MailVerifController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PdfsController;
use App\Models\Alumno;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




Route::prefix('alumno')->group(function(){
    Route::get('/constancia', [PdfsController::class, 'constanciaMesas'])->name('alumno.constancia');
    Route::get('/analitico', [PdfsController::class, 'analitico'])->name('alumno.analitico');

    Route::get('/registro',[AlumnoAuthController::class,'registroView'])->name('alumno.registro');
    Route::post('/registro',[AlumnoAuthController::class,'registro'])->name('alumno.registro.post');

    Route::get('/login',[AlumnoAuthController::class,'loginView'])->name('alumno.login');
    Route::post('/login',[AlumnoAuthController::class,'login'])->name('alumno.login.post');

    Route::get('/logout',[AlumnoAuthController::class,'logout'])->name('alumno.logout');

    Route::get('/mail', [MailVerifController::class,'enviarMail'])->name('token.enviar.mail');

    Route::get('/token', [MailVerifController::class, 'ingresarTokenView'])->name('token.ingreso');
    Route::post('/token', [MailVerifController::class, 'verificarToken'])->name('token.ingreso.post');

    Route::get('/info', [AlumnoController::class, 'info'])->name('alumno.info');
    Route::get('/cursadas', [AlumnoController::class, 'cursadas'])->name('alumno.cursadas');
    Route::get('/examenes', [AlumnoController::class, 'examenes'])->name('alumno.examenes');
    Route::get('/inscripciones', [AlumnoController::class, 'inscripciones'])->name('alumno.inscripciones');
    Route::get('/inscripciones', [AlumnoController::class, 'inscripciones'])->name('alumno.inscripciones');
    Route::post('/inscribirse', [AlumnoController::class, 'inscribirse'])->name('alumno.inscribirse');
    Route::post('/bajarse', [AlumnoController::class, 'bajarse'])->name('alumno.bajarse');

    Route::post('/set-default', [AlumnoController::class, 'setCarreraDefault'])->name('alumno.set.default');

    Route::get('/reset',[PasswordResetController::class,'vista'])->name('reset.password');
    Route::get('/reset/mail',[PasswordResetController::class,'mail'])->name('reset.password.mail');

    Route::post('/reset',[PasswordResetController::class,'validarToken'])->name('reset.password.post');

    Route::post('/cambiarpw',[AlumnoAuthController::class, 'cambiarPassword'])->name('cambio.password');

    Route::get('/rematriculacion', [AlumnoController::class,'rematriculacion_vista'])->name('alumno.rematriculacion');
});