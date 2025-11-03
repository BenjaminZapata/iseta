<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Cursada;
use App\Models\Examen;
use App\Models\Mesa;
use App\Services\Admin\CursadaRegularService;
use App\Services\Admin\Pdfs\RegistroAvancePdf;
use Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Spatie\LaravelPdf\Support\pdf;

class AdminPdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function acta_volante(Request $request, Mesa $mesa)
    {
        // Traigo todos los exámenes de esa mesa que cumplan con condicion == 1
        $examenes = Examen::with('alumno')
            ->where('id_mesa', $mesa->id)
            ->get()
            ->filter(function ($examen) {
                // Verifico que exista cursada con condicion == 1 para el alumno y asignatura
                return Cursada::where('id_alumno', $examen->id_alumno)
                    ->where('id_asignatura', $examen->id_asignatura)
                    ->where('condicion', 1)
                    ->exists();
            });

        return pdf()
            ->view('Pdf.acta-volante', [
                'examenes' => $examenes,
                'mesa' => $mesa,
                'condicion' => 'REGULAR',
            ])
            ->name('acta-volante-regular.pdf');
    }

    public function actaVolantePromocion(Request $request, Mesa $mesa)
    {
        $alumnos = [];

        $examenes = $mesa->examenes()
            ->whereRelation('alumno.cursadas', function ($query) use ($mesa) {
                $query->where('id_asignatura', $mesa->id_asignatura)
                    ->where('id_carrera', $mesa->id_carrera)
                    ->where('condicion', 2);
            })
            ->with(['alumno.cursadas' => function ($query) use ($mesa) {
                $query->where('id_asignatura', $mesa->id_asignatura)
                    ->where('id_carrera', $mesa->id_carrera)
                    ->where('condicion', 2);
            }])
            ->get();
        Log::info($examenes);

        return pdf()
            ->view('Pdf.acta-volante', compact('alumnos', 'examenes') + ['mesa' => $mesa, 'condicion' => 'PROMOCION'])
            ->name('acta-volante-promocion.pdf');
    }

    public function actaVolanteLibre(Request $request, Mesa $mesa)
    {
        $alumnos = [];

        // Todos los registros de alumnos en esa mesa
        $examenes = $mesa->examenes()
            ->whereRelation('alumno.cursadas', function ($query) use ($mesa) {
                $query->where('id_asignatura', $mesa->id_asignatura)
                    ->where('id_carrera', $mesa->id_carrera)
                    ->where('condicion', 0);
            })
            ->with(['alumno.cursadas' => function ($query) use ($mesa) {
                $query->where('id_asignatura', $mesa->id_asignatura)
                    ->where('id_carrera', $mesa->id_carrera)
                    ->where('condicion', 0);
            }])
            ->get();

        // para cada registro
        return pdf()
            ->view('Pdf.acta-volante', compact('alumnos', 'examenes') + ['mesa' => $mesa, 'condicion' => 'LIBRE'])
            ->name('acta-volante-libre.pdf');
    }

    public function constanciaRegular(Alumno $alumno, Carrera $carrera, Configuracion $config)
    {
        $checker = new CursadaRegularService($alumno, $config);
        $regular = $checker->esCursadaRegular();
        if (! $regular) {
            return redirect()->back()->with('aviso', 'El alumno no tiene condicion de regular');
        }
        $fecha = Carbon\Carbon::now();

        return pdf()
            ->view('Pdf.alumno-regular', compact('alumno') + ['fecha' => $fecha, 'cursada' => $regular['cursada'], 'inscripto' => $regular['inscripto']])
            ->format('a4')
            ->name('constancia-regular.pdf');
        // ->download();
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

        $porcentaje = number_format(count($examenes) / max(count($materias), 1) * 100, 2, '.', '').'%';

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

        // Detectar formato de hoja según la cantidad de años
        $maxAnio = $materias->max('anio');
        $formato = $maxAnio > 5 ? 'legal' : 'a4';

        // Imagen en base64
        $imgPath = public_path('img/pdf.png');
        $src = 'data:image/png;base64,'.base64_encode(file_get_contents($imgPath));

        // Mostrar el PDF en el navegador
        return pdf()
            ->view('Pdf.analitico', [
                'alumno' => $alumno,
                'carrera' => $carrera,
                'resolucion' => $carrera->resolucion,
                'materias' => $materiasExamenes,
                'porcentaje' => $porcentaje,
                'src' => $src,
            ])
            ->format($formato)
            ->name("analitico_{$alumno->apellido}_{$alumno->nombre}.pdf");
    }

    public function registroDeAvance(string $cursada_group)
    {
        [$id_carrera, $id_asignatura, $anio] = explode('-', $cursada_group);
        $cursadas = Cursada::with(['alumno', 'asignatura', 'carrera'])
            ->where('id_carrera', $id_carrera)
            ->where('id_asignatura', $id_asignatura)
            ->where('anio_cursada', $anio)
            ->get();
        $pdfBuilder = new RegistroAvancePdf;
        $pdfBuilder->build($cursadas);
        $pdf = $pdfBuilder->getPdf();

        return response($pdf->Output('registro.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }
}
