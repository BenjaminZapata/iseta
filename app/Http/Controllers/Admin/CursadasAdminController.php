<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CursadaUpdateRequest;
use App\Models\Alumno;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Correlativa;
use App\Models\Cursada;
use App\Models\Examen;
use App\Repositories\Admin\CursadaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\log;
use Illuminate\Validation\Rule;

class CursadasAdminController extends BaseController
{
    public $defaultFilters = [
        'filter_carrera_id' => 0,
        'filter_asignatura_id' => 0,
        'filter_alumno_id' => 0,
        'filter_condicion' => null,
        'filter_aprobada' => 0,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
    }

    public function index(Request $request, CursadaRepository $cursadaRepo)
    {
        $this->setFilters($request);
        $this->data['cursadas'] = $cursadaRepo->index($request);
        session(['return_to' => url()->previous()]);
        $request->flash();

        return view('Admin.Cursadas.index', $this->data);
    }

    public function delete(Cursada $cursada)
    {
        $cursada->delete();

        return redirect()->route('admin.cursadas.index');
    }

    public function edit(Request $request, Cursada $cursada)
    {
        $nota = Examen::where('id_carrera', $cursada->id_carrera)
            ->where('id_asignatura', $cursada->id_asignatura)
            ->where('id_alumno', $cursada->id_alumno)
            ->value('nota'); // Equivalencia
        Log::debug('message', ['cursada' => $cursada]);

        return view('Admin.Cursadas.edit', compact('cursada') + ['nota' => $nota]);
    }

    public function update(CursadaUpdateRequest $request, Cursada $cursada)
    {
        $mensajes = [];

        // Validación de año de cursada
        $data = $request->validated();

        if ($request->aprobada == 5) {

            Examen::updateOrInsert(
                [
                    'id_carrera' => $cursada->id_carrera,
                    'id_asignatura' => $cursada->id_asignatura,
                    'id_alumno' => $cursada->id_alumno,
                ],
                [
                    'id_carrera' => $cursada->id_carrera,
                    'id_asignatura' => $cursada->id_asignatura,
                    'id_alumno' => $cursada->id_alumno,
                    'tipo_final' => 4, // Equivalencia
                    'nota' => $request->nota,
                    'aprobado' => 1,
                ]
            );
        }

        $cursada->update([
            'condicion' => $request->condicion,
            'primer_cuatrimestre_nota' => $data['primer_cuatrimestre_nota'],
            'segundo_cuatrimestre_nota' => $data['segundo_cuatrimestre_nota'],
            'aprobada' => $data['aprobada'],
            'observaciones' => $data['observaciones'],
        ]);
        $mensajes[] = 'Se ha editado correctamente';

        return redirect()->back()->with('mensaje', $mensajes);
    }

    public function create(request $request)
    {
        $alumnos = Alumno::orderBy('nombre', 'asc')->orderBy('apellido', 'asc')->get();
        $carreras = Carrera::vigentes();

        return view('Admin/Cursadas/create', [
            'alumnos' => $alumnos,
            'carreras' => $carreras,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'carrera' => ['required'],
            'asignatura' => ['required'],
            'alumno' => ['required'],
            'anio_cursada' => ['required', 'integer',
                Rule::numeric()->exactly(now()->year),
            ],
        ]);

        $asignatura = Asignatura::where('id', $request->asignatura)->with('correlativas.asignatura')->first();
        $alumno = Alumno::find($request->alumno);
        if (! $asignatura) {
            return redirect()->back()->with('error', 'La asignatura seleccionada no existe')->withInput();
        }

        // Ver que no este ya anotado o que ya la haya aprobado
        $yaAnotadoEnCursada = Cursada::where('id_alumno', $alumno->id)
            ->whereRaw('(aprobada=3 OR aprobada=1)')
            ->where('id_asignatura', $asignatura->id)
            ->first();

        // Si lo esta, no incluir
        if ($yaAnotadoEnCursada) {
            return \redirect()->back()->with('error', 'El alumno ya registra una cursada de la asignatura del año '.$yaAnotadoEnCursada->anio_cursada)->withInput();
        }

        // Obtener datos de la asignatura con sus correlativas
        $correlativas = Correlativa::debeCursadasCorrelativos($asignatura, $alumno);

        if ($correlativas) {
            $mensajes = [];
            foreach ($correlativas as $correlativa) {
                $mensajes[] = 'Debe la cursada de '.$correlativa->nombre;
            }

            return \redirect()->back()->with(['error' => $mensajes])->withInput();
        }
        // WARN: Aprobado por equivalencia se crea un examen sin mesa (faltan datos)
        if ($request->aprobada == 5) {
            Examen::create([
                'id_carrera' => $request->carrera,
                'id_asignatura' => $request->asignatura,
                'id_alumno' => $request->alumno,
                'tipo_final' => 4, // Equivalencia
                // 'libro' => $request->libro,
                // 'acta' => $request->acta,
                'nota' => $request->nota,
                //  'fecha' => $request->fecha,
                'aprobado' => 1,
            ]);
        }

        Cursada::create([
            'id_carrera' => $request->carrera,
            'id_asignatura' => $request->asignatura,
            'id_alumno' => $request->alumno,
            'anio_cursada' => $request->anio_cursada,
            'condicion' => $request->condicion,
            'aprobada' => $request->aprobada,
        ]);

        return redirect()->back()->with('mensaje', 'Se creo la cursada');
    }

    public function destroy(Cursada $cursada)
    {
        try {
            if ($cursada->anio_cursada == now()->year && $cursada->alumno->egresadoinscripto()->where('id_carrera', $cursada->id_carrera)->first()->estado != 2) {
                flash()->warning('No se puede eliminar una cursada que pertenezca al año lectivo actual, a no ser que el alumno tenga como condicion "Desertor"');

                return back();
            }

            if ($cursada->aprobada == 5) {
                Examen::where('id_carrera', $cursada->id_carrera)
                    ->where('aprobado', 1)
                    ->where('id_asignatura', $cursada->id_asignatura)
                    ->where('id_alumno', $cursada->id_alumno)
                    ->delete();
            }

            if ($cursada->primer_cuatrimestre_nota != null || $cursada->segundo_cuatrimestre_nota != null) {
                flash()->warning('No se puede eliminar la cursada porque el alumno cuenta con notas en los cuatrimestres');

                return back();
            }

            $cursada->delete();

            return redirect()->route('admin.cursadas.index')
                ->with('mensaje', 'Se ha eliminado la cursada');
        } catch (\Exception $e) {
            \Log::error($e);

            return flash()->error('No se pudo eliminar la cursada.');
        }
    }
}
