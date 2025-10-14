<?php

use App\Http\Controllers\MailVerifController;
use App\Http\Controllers\ProfesoresAuthController;
use App\Http\Controllers\ProfesoresController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;


Route::prefix('profesor')->group(function () {
    Route::get('registro', [ProfesoresAuthController::class, 'registroView'])->name('profesor.register');
    Route::post('registro', [ProfesoresAuthController::class, 'registro'])->name('profesor.register.post');

    Route::get('/mail', [MailVerifController::class, 'enviarMailProfe'])->name('token.enviar.mail.profe');
    Route::get('/token', [MailVerifController::class, 'ingresarTokenViewProfe'])->name('token.ingreso.profe');
    Route::post('/token', [MailVerifController::class, 'verificarTokenProfe'])->name('token.ingreso.post.profe');

    Route::get('login', [ProfesoresAuthController::class, 'loginView'])->name('profesor.login');
    Route::post('login', [ProfesoresAuthController::class, 'login'])->name('profesor.login.post');

    Route::get('/logout', [ProfesoresAuthController::class, 'logout'])->name('profesor.logout');

    Route::get('mesas', [ProfesoresController::class, 'mesas'])->name('profesor.mesas');

    Route::get('/reset', [PasswordResetController::class, 'vistaProfe'])->name('reset.password.profe');
    Route::get('/reset/mail', [PasswordResetController::class, 'mailProfe'])->name('reset.password.mail.profe');
    Route::post('/reset', [PasswordResetController::class, 'validarTokenProfe'])->name('reset.password.post.profe');
});
