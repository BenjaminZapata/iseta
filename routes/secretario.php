<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Secretario\AlumnoSecretarioController;
use App\Http\Controllers\Secretario\ProfesoresSecretarioController;

Route::prefix('secretario')
    ->middleware(['auth:admin'])
    ->name('secretario.')
    ->group(function () {

       //alumnos
        Route::get('alumnos', [AlumnoSecretarioController::class, 'index'])
            ->name('alumnos.index');
        Route::get('alumnos/{alumno}', [AlumnoSecretarioController::class, 'show'])
            ->name('alumnos.show');

        //profesores
        Route::get('profesores', [ProfesoresSecretarioController::class, 'index'])
            ->name('profesores.index');
        Route::get('profesores/create', [ProfesoresSecretarioController::class, 'create'])
            ->name('profesores.create');
        Route::post('profesores', [ProfesoresSecretarioController::class, 'store'])
            ->name('profesores.store');
        Route::get('profesores/{profesor}/edit', [ProfesoresSecretarioController::class, 'edit'])
            ->name('profesores.edit');
        Route::put('profesores/{profesor}', [ProfesoresSecretarioController::class, 'update'])
            ->name('profesores.update');
        Route::delete('profesores/{profesor}', [ProfesoresSecretarioController::class, 'destroy'])
            ->name('profesores.destroy');
        


    });
