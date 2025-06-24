<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Alumno;
use App\Models\Cursada;
use App\Models\Examen;
use App\Models\Mesa;
use Barryvdh\DomPDF\Facade\Pdf;
use function Spatie\LaravelPdf\Support\pdf;

use Illuminate\Http\Request;

class AdminPdfController extends Controller
{
    function acta_volante(Request $request,Mesa $mesa){

        $alumnos = [];

        $examenes = Examen::where('id_mesa', $mesa->id)->get();

        foreach ($examenes as $examen) {
            $cursadas = Cursada::where('id_alumno',$examen->id_alumno)
                -> where('id_asignatura', $examen->id_asignatura)
                -> get();

            foreach ($cursadas as $cursada) {
                if($cursada->condicion==1){
                    $alumnos[]=Alumno::find($examen->id_alumno);
                }
            }
        }
        // $alumnos = Mesa::select('examenes.id as id_examen','alumnos.nombre','alumnos.dni','alumnos.apellido','examenes.nota')
        // -> join('examenes', 'examenes.id_mesa','mesas.id')
        // -> join('alumnos', 'alumnos.id','examenes.id_alumno')
        // -> where('mesas.id', $mesa->id)
        // -> get();



        $pdf = Pdf::loadView('pdf.acta-volante', ['alumnos' => $alumnos,'mesa' => $mesa,'condicion'=>'']);
        return $pdf->stream('invoice.pdf');
    }

    function actaVolantePromocion(Request $request,Mesa $mesa){
        $alumnos = [];

        $examenes = Examen::where('id_mesa', $mesa->id)->get();

        foreach ($examenes as $examen) {
            $cursadas = Cursada::where('id_alumno',$examen->id_alumno)
                -> where('id_asignatura', $examen->id_asignatura)
                -> get();

            foreach ($cursadas as $cursada) {
                if($cursada->condicion == 2){
                    $alumnos[]=Alumno::find($examen->id_alumno);
                }
            }
        }

        $pdf = Pdf::loadView('pdf.acta-volante', ['alumnos' => $alumnos,'mesa' => $mesa,'condicion'=>'PROMOCION']);
        return $pdf->stream('acta-volante.pdf');
    }

    function actaVolanteLibre(Request $request,Mesa $mesa){
        $alumnos = [];

        // Todos los registros de alumnos en esa mesa
        $examenes = Examen::where('id_mesa', $mesa->id)->get();

        // para cada registro
        foreach ($examenes as $examen) {

            // Buscar cursadas de ese alumno y de la materia de la mesa
            $cursadas = Cursada::where('id_alumno',$examen->id_alumno)
                -> where('id_asignatura', $examen->id_asignatura)
                -> get();

            // si la cursada figura como libre, se muestra.
            foreach ($cursadas as $cursada) {
                if($cursada->condicion==0){
                    $alumnos[]=Alumno::find($examen->id_alumno);
                }
            }
        }

        $pdf = Pdf::loadView('pdf.acta-volante', ['alumnos' => $alumnos,'mesa' => $mesa,'condicion'=>'LIBRE']);
        return $pdf->stream('acta-volante.pdf');
    }
    function constanciaRegular(Request $request){
        $alumno = $request->alumno;
        $cursadas = Asignatura::selectRaw('asignaturas.id, asignaturas.nombre, asignaturas.anio, MAX(cursadas.aprobada) as aprobada')
            -> from('asignaturas')
            -> join('cursadas','cursadas.id_asignatura','=','asignaturas.id')
            -> where('cursadas.id_alumno', $alumno)
            -> where('cursadas.aprobada', '>=', 1)
            -> groupBy('asignaturas.id','asignaturas.nombre','asignaturas.anio')
            -> get();

        return pdf()
        ->view('Pdf.alumno-regular', ['cursadas' => $cursadas])
        ->name('constancia-regular.pdf');
    }
}
