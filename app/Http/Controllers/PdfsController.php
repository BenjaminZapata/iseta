<?php


namespace App\Http\Controllers;

use Spatie\LaravelPdf\Facades\Pdf;



use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Examen;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


class PdfsController extends Controller
{
    function __construct()
    {
        $this -> middleware('auth:web');
        $this -> middleware('verificado');
    }
    
    function constanciaMesas(){
        $alumno = Auth::id();


        $mesas = Examen::where('id_alumno', $alumno)
            -> join('mesas','mesas.id','examenes.id_mesa')
            -> whereRaw('mesas.fecha >= NOW()')
            -> get();


        // Define $invoice or replace with appropriate data
        $invoice = []; // Replace with actual data as needed

        return Pdf::view('pdfs.invoice',['invoice'=> $invoice])
            ->format('a4')
            ->save('invoice.pdf');
    }


    function constanciaRegular(){
        $alumno = Auth::id();

        $cursadas = Asignatura::selectRaw('asignaturas.id, asignaturas.nombre, asignaturas.anio, MAX(cursadas.aprobada) as aprobada')
            -> from('asignaturas')
            -> join('cursadas','cursadas.id_asignatura','=','asignaturas.id')
            -> where('cursadas.id_alumno', $alumno)
            -> where('cursadas.aprobada', '>=', 1)
            -> groupBy('asignaturas.id','asignaturas.nombre','asignaturas.anio')
            -> get();

        $pdf = Pdf::loadView('pdf.constanciaRegular', ['cursadas' => $cursadas]);
        return $pdf->stream('invoice.pdf');
    }

    function analitico($invoice){
        $alumno = Auth::id();


        $id_carrera = Carrera::getDefault()->id;


        $materias = Asignatura::where('id_carrera', $id_carrera)->get();

         $materiasExamenes = [];

        $examenes = Examen::selectRaw('examenes.id_asignatura, asignaturas.nombre, MAX(examenes.nota) as nota, asignaturas.anio, examenes.fecha')
        -> from('asignaturas')
        -> join('examenes','examenes.id_asignatura','=','asignaturas.id')
        -> where('examenes.id_alumno', Auth::id())
        -> where('asignaturas.id_carrera', Carrera::getDefault()->id)
        -> where('examenes.nota', '>=', 4)
        -> groupBy('examenes.id_asignatura','asignaturas.nombre','asignaturas.anio','examenes.fecha')
        -> get();


        $porcentaje = number_format((float) count($examenes) / count($materias) * 100, 2, '.', ''). '%';

        foreach($materias as $key => $materia){
            foreach($examenes as $examen){
                if($materia->id == $examen->id_asignatura){

                    $copia = $materia;
                    $copia->{'examen'} = $examen;
                    $materia = $copia;
                    continue;
                }
            }
            $materiasExamenes[] = $materia;
        }
        
        $pdf = Pdf::loadView('pdf.analitico', [
            'materias' => $materias,
            'carrera' => Carrera::where('id',$id_carrera)->first(),
            'porcentaje' => $porcentaje
        ]);

        return $pdf->stream('invoice.pdf');
    }
}
