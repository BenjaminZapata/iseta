<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Alumno;
use App\Models\Cursada;
use App\Models\Examen;
use App\Models\Mesa;
use App\Models\Carrera;
use App\Models\Configuracion;
use Carbon;
use function Spatie\LaravelPdf\Support\pdf;
use App\Services\Admin\CursadaRegularService;

use Illuminate\Http\Request;
use PSpell\Config;

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


        return pdf()
        ->view('pdf.acta-volante', compact('alumnos') + ['mesa' => $mesa,'condicion'=>''])
        ->name('acta-volante-regular.pdf');
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

        return pdf()
        ->view('pdf.acta-volante', compact('alumnos') + ['mesa' => $mesa,'condicion'=>'PROMOCION'])
        ->name('acta-volante-promocion.pdf');
    }

    public function actaVolanteLibre(Request $request,Mesa $mesa){
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
        return pdf()
        ->view('Pdf.acta-volante', compact('alumnos') + ['mesa' => $mesa,'condicion'=>'LIBRE'])
        ->name('acta-volante-libre.pdf');

    }
    public function constanciaRegular(Alumno $alumno, Configuracion $config) {
        $regular = new CursadaRegularService($alumno, $config);
        if (!$regular->esCursadaRegular()) {
            return redirect()->back()->with('aviso', 'El alumno no tiene condicion de regular');
        }
        $fecha = Carbon\Carbon::now();
        return pdf()
        ->view('Pdf.alumno-regular', compact('alumno') + ['fecha' => $fecha])
        ->name('constancia-regular.pdf');
        //->download();
    }

    public function analitico(Alumno $alumno)
    {
        $carrera = Carrera::getDefault($alumno->id);
        $id_carrera = $carrera?->id;
        $materias = Asignatura::whereHas('carrera', function ($query) use ($id_carrera) {
            $query->where('id', $id_carrera);
        })->get();

        $examenes = Examen::selectRaw('examenes.id_asignatura, asignaturas.nombre, MAX(examenes.nota) as nota, asignaturas.anio, examenes.fecha')
            ->from('asignaturas')
            ->join('examenes', 'examenes.id_asignatura', '=', 'asignaturas.id')
            ->where('examenes.id_alumno', $alumno->id)
            ->join('carrera_asignatura_profesor as cap', 'asignaturas.id', '=', 'cap.id_asignatura')
            ->where('cap.id_carrera', $id_carrera)
            ->where('examenes.nota', '>=', 4)
            ->groupBy('examenes.id_asignatura', 'asignaturas.nombre', 'asignaturas.anio', 'examenes.fecha')
            ->get();

        $porcentaje = number_format(count($examenes) / max(count($materias), 1) * 100, 2, '.', '') . '%';

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

        // Imagen en base64
        $imgPath = public_path('img/pdf.png');
        $src = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath));

        // Mostrar el PDF en el navegador
        return pdf()
            ->view('Pdf.analitico', [
                'alumno' => $alumno,
                'carrera' => $carrera,
                'materias' => $materiasExamenes,
                'porcentaje' => $porcentaje,
                'src' => $src,
            ])
            ->format('a4')
            ->name("analitico_{$alumno->apellido}_{$alumno->nombre}.pdf");
    }

}
