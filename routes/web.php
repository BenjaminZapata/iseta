<?php

use App\Models\Examen;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'alumno/inscripciones');


include("alumnos.php");
include("admin.php");
include("profesores.php");
include("test.php");
include("preceptor.php");

Route::fallback(function () {
    return response()->view('Error.404', [], 404);
});
