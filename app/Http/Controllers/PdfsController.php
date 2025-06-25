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
        $this -> middleware('verificado');
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


    function analitico(Alumno $alumno)
{
    $id_carrera = Carrera::getDefault()->id;
    $materias = Asignatura::where('id_carrera', $id_carrera)->get();

    $examenes = Examen::selectRaw('examenes.id_asignatura, asignaturas.nombre, MAX(examenes.nota) as nota, asignaturas.anio, examenes.fecha')
        ->from('asignaturas')
        ->join('examenes', 'examenes.id_asignatura', '=', 'asignaturas.id')
        ->where('examenes.id_alumno', $alumno->id)
        ->where('asignaturas.id_carrera', $id_carrera)
        ->where('examenes.nota', '>=', 4)
        ->groupBy('examenes.id_asignatura', 'asignaturas.nombre', 'asignaturas.anio', 'examenes.fecha')
        ->get();

    $porcentaje = number_format((float) count($examenes) / count($materias) * 100, 2, '.', '') . '%';

    $materiasExamenes = [];
    foreach ($materias as $materia) {
        foreach ($examenes as $examen) {
            if ($materia->id == $examen->id_asignatura) {
                $copia = clone $materia;
                $copia->examen = $examen;
                $materiasExamenes[] = $copia;
                break;
            }
        }
    }

    return Pdf::view('pdfs.analitico', [
        'materias' => $materiasExamenes,
        'porcentaje' => $porcentaje,
        'alumno_id' => $alumno->id,
    ])
    ->format('a4')
    ->download("analitico_{$alumno->apellido}_{$alumno->nombre}.pdf");
}

}
