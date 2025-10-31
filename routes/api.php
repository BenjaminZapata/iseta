<?php

use App\Models\Alumno;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Correlativa;
use App\Services\TextFormatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/a/{carrera}',function(Request $request, Carrera $carrera){
    return $carrera->asignaturas;
});

Route::get('cursadas/alumnos/{asignatura}',function(Request $request, $asignatura){
  Alumno::all();

});
Route::get('/asignatura/{id}/presidente', function ($id) {
    $relacion = \DB::table('carrera_asignatura_profesor')
        ->where('id_asignatura', $id)
        ->first();

    return response()->json([
        'presidente_id' => $relacion?->id_profesor ?? 0
    ]);
});
