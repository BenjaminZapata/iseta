<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Examen;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\Alumno;


class PdfsController extends Controller
{
    function __construct()
    {
        $this -> middleware('auth:admin');
        // $this -> middleware('verificado');
    }
    
    function constanciaMesas(){
        $alumno = Auth::id();


        $mesas = Examen::where('id_alumno', $alumno)
            -> join('mesas','mesas.id','examenes.id_mesa')
            -> whereRaw('mesas.fecha >= NOW()')
            -> get();

       return Pdf::view('pdfs.constancia_mesas', [
        'mesas' => $mesas,
        'alumno_id' => $alumno,
    ])
    ->format('a4')
    ->name('constancia-mesas.pdf');
    }
}
