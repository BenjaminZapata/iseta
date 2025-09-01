<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Secretario\AlumnoSecretarioController;

Route::prefix('secretario')
    ->name('secretario.')
    ->middleware(['auth', 'role:secretario']) // si usás middleware de rol
    ->group(function () {
        // Listado de alumnos
        Route::get('alumnos', [AlumnoSecretarioController::class, 'index'])
            ->name('alumnos.index');

        // Ver detalle de un alumno
        Route::get('alumnos/{alumno}', [AlumnoSecretarioController::class, 'show'])
            ->name('alumnos.show');
    });
