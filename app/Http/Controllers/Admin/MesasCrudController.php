<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CrearMesaRequest;
use App\Http\Requests\EditarMesaRequest;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Mesa;
use App\Models\Profesor;
use App\Repositories\Admin\mesaRepo;
use App\Repositories\Admin\MesaRepository;
use App\Services\Admin\MesasCheckerService;
use App\Services\DiasHabiles;
use DateInterval;
use DateTime;
use App\Models\Alumno;
use Illuminate\Http\Request;

class MesasCrudController extends BaseController
{
    public $defaultFilters = [
        'filter_carrera_id' => 0,
        'filter_asignatura' => 0,
        'filter_alumno_id' => 0,
        'filter_llamado' => 0,
        'filter_presidente' => 0,
        'filter_vocal1' => 0,
        'filter_vocal2' => 0,
        'filter_from' => null,
        'filter_to' => null
    ];

    public $mesaRepo;
    public $mesasService;

    function __construct(MesaRepository $mesaRepo, MesasCheckerService $mesasService)
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->mesaRepo = $mesaRepo;
        $this->mesasService = $mesasService;
    }

    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    // 🕒 Actualizar mesas vencidas (solo si fecha ya pasó)
    Mesa::whereDate('fecha', '<', now())
        ->where('estado', 0)
        ->update(['estado' => 1]);

    // 🔍 Aplicar filtros y cargar datos
    $this->setFilters($request);
    $this->data['mesas'] = $this->mesaRepo->index($request);

    return view('Admin.Mesas.index', $this->data);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $precargados = [
            'carrera' => $request->input('carrera'),
            'asignatura' => $request->has('asignatura') ? Asignatura::find($request->input('asignatura')) : null,
        ];

        $carreras = Carrera::where('vigente', 1)->with('asignaturas')->get();
        $profesores = Profesor::orderBy('apellido')->orderBy('nombre')->get();

        // Carrera seleccionada
        $carrera_previa = $carreras->first(function ($c) use ($precargados) {
            return $c->id == $precargados['carrera'] || $c->id == old('carrera');
        });

        // Asignatura seleccionada
        $asignatura_previa = $precargados['asignatura'] ?: (
            $carrera_previa
                ? $carrera_previa->asignaturas->firstWhere('id', old('id_asignatura'))
                : null
        );

        // Opciones para selects
        $opcionesCarreras = $carreras->mapWithKeys(fn($c) => [$c->id => $c->nombre])->prepend('Selecciona una carrera', 'any');
        $opcionesAsignaturas = collect();
        if ($asignatura_previa) {
            $opcionesAsignaturas->put($asignatura_previa->id, $asignatura_previa->nombre);
        }
        $opcionesAsignaturas->put('', 'Selecciona una carrera');

        $opcionesProfesores = $profesores->mapWithKeys(fn($p) => [$p->id => $p->apellido . ' ' . $p->nombre])->prepend('Vacío/A confirmar', 0);

        return view('Admin.Mesas.create', [
            'opcionesCarreras' => $opcionesCarreras,
            'opcionesAsignaturas' => $opcionesAsignaturas,
            'opcionesProfesores' => $opcionesProfesores,
            'oldCarrera' => old('carrera', $precargados['carrera']),
            'oldAsignatura' => old('id_asignatura', optional($asignatura_previa)->id),
            'oldPresidente' => old('prof_presidente', 0),
            'oldVocal1' => old('prof_vocal_1', 0),
            'oldVocal2' => old('prof_vocal_2', 0),
            'oldCantidadLlamados' => old('cantidad_llamados', 1),
            'oldFecha1' => old('fecha_1'),
            'oldFecha2' => old('fecha_2'),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearMesaRequest $request)
    {

        // obtener datos validados
        $data = $request->validated();

        $esDiaValido = $this->mesasService->esDiaHabil($data['fecha_1']);

        if (!$esDiaValido['success']) {
            return redirect()->back()->with('error', $esDiaValido['mensaje'])->withInput();
        }

        $llamadoYaExiste = $this->mesasService->llamadoYaExiste([
            'id_asignatura' => $data['id_asignatura'],
            'fecha' => $data['fecha_1'],
            'llamado' => 1
        ]);

        if ($llamadoYaExiste['success']) {
            return redirect()->back()->with('error', $llamadoYaExiste['mensaje'])->withInput();
        }

        // Que los profes no sean los mismos
        if (
            $data['prof_presidente'] == $data['prof_vocal_1'] ||
            $data['prof_presidente'] == $data['prof_vocal_2'] ||
            $data['prof_vocal_1'] == $data['prof_vocal_2'] && $data['prof_vocal_1'] != '0'
        ) {
            return redirect()->back()->with('error', 'Hay profesores repetidos');
        }
        if ($data['cantidad_llamados'] == 2) {

            $esDiaValido = $this->mesasService->esDiaHabil($data['fecha_2']);
            if (!$esDiaValido['success']) {
                return redirect()->back()->with('error', $esDiaValido['mensaje'])->withInput();
            }
            $llamadoYaExiste = $this->mesasService->llamadoYaExiste([
                'id_asignatura' => $data['id_asignatura'],
                'fecha' => $data['fecha_2'],
                'llamado' => 2
            ]);

            if ($llamadoYaExiste['success']) {
                return redirect()->back()->with('error', $llamadoYaExiste['mensaje'])->withInput();
            }
            Mesa::create([
                'id_carrera' => $data['carrera'],
                'id_asignatura' => $data['id_asignatura'],
                'fecha' => $data['fecha_2'],
                'llamado' => 2,
                'prof_presidente' => $data['prof_vocal_1'],
                'prof_vocal_1' => $data['prof_vocal_2'],
                'prof_vocal_2' => $data['prof_presidente'],
                'estado' => 0 
            ]);
        }

        Mesa::create([
            'id_carrera' => $data['carrera'],
            'id_asignatura' => $data['id_asignatura'],
            'fecha' => $data['fecha_1'],
            'llamado' => 1,
            'prof_presidente' => $data['prof_presidente'],
            'prof_vocal_1' => $data['prof_vocal_1'],
            'prof_vocal_2' => $data['prof_vocal_2'],
            'estado' => 0
        ]);
        return \redirect()->back()->with('mensaje', 'Se creo la mesa');
    }


    /**
     * Show the form for editing the specified resource.
     */
public function edit($id)
{
    // 🔹 Traer la mesa con relaciones necesarias
    $mesa = Mesa::with([
        'asignatura.carrera',
        'profesor',
        'vocal1',
        'vocal2',
        'examenes.alumno'
    ])->findOrFail($id);

    // 🔹 Todos los profesores
    $profesores = Profesor::orderBy('apellido')->orderBy('nombre')->get();
    $opcionesProfesores = $profesores
        ->mapWithKeys(fn($p) => [(int)$p->id => $p->apellido . ' ' . $p->nombre])
        ->prepend('Vacío/A confirmar', 0)
        ->toArray();

    // 🔹 Valores seleccionados
    $selectedPresidente = $mesa->prof_presidente ?? optional($mesa->profesor)->id ?? 0;
    $selectedVocal1     = $mesa->prof_vocal_1 ?? optional($mesa->vocal1)->id ?? 0;
    $selectedVocal2     = $mesa->prof_vocal_2 ?? optional($mesa->vocal2)->id ?? 0;

    // 🔹 ID de carrera (para el JS)
    $carrera_id = $mesa->asignatura->carrera instanceof \Illuminate\Database\Eloquent\Collection
        ? $mesa->asignatura->carrera->first()->id
        : $mesa->asignatura->carrera->id;

    // 🔹 Alumnos inscribibles (aprobados en cursada)
    $inscribibles = Alumno::whereHas('cursadas', function ($q) use ($mesa, $carrera_id) {
        $q->where('id_asignatura', $mesa->id_asignatura)
          ->where('id_carrera', $carrera_id)
          ->where('aprobada', 1);
    })->get();

    // 🔹 Pasar todo a la vista
    return view('Admin.Mesas.edit', [
        'mesa' => $mesa,
        'opcionesProfesores' => $opcionesProfesores,
        'selectedPresidente' => $selectedPresidente,
        'selectedVocal1' => $selectedVocal1,
        'selectedVocal2' => $selectedVocal2,
        'inscribibles' => $inscribibles,
        'carrera_id' => $carrera_id,
    ]);
}


    /**
     * Update the specified resource in storage.
     */
public function update(EditarMesaRequest $request, Mesa $mesa)
{
    $data = $request->validated();

    // Validaciones de día hábil...
    if (DiasHabiles::esFinDeSemana($data['fecha'])) {
        return redirect()->back()->with('error', 'La fecha es fin de semana');
    }

    if (!DiasHabiles::esDiaHabil($data['fecha'])) {
        return redirect()->back()->with('error', 'La fecha es un día no hábil');
    }

    if (
        $data['prof_presidente'] == $data['prof_vocal_1'] ||
        $data['prof_presidente'] == $data['prof_vocal_2'] ||
        ($data['prof_vocal_1'] == $data['prof_vocal_2'] && $data['prof_vocal_1'] != '0')
    ) {
        return redirect()->back()->with('error', 'Hay profesores repetidos');
    }

    // 🔹 Actualiza todos los campos validados (incluido llamado)
    $mesa->update($data);

    return redirect()->back()->with('mensaje', 'Se editó la mesa');
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mesa $mesa)
    {
        try {

            //verificar que no tenga fecha hoy o futura
            if ($mesa->fecha >= date('Y-m-d')) {
                return redirect()->route('admin.mesas.index')
                    ->with('error', 'No se pudo eliminar la mesa. Tiene fecha hoy o futura.');
            }

            //Verificar que no tenga alumnos inscriptos
            if ($mesa->examenes()->exists()) {
                return redirect()->route('admin.mesas.index')
                    ->with('error', 'No se pudo eliminar la mesa. Tiene alumnos inscriptos.');
            }

            //eliminar mesa
            $mesa->delete();
            return redirect()->route('admin.mesas.index')
                ->with('mensaje', 'Se ha eliminado la mesa');
        } catch (\Exception $e) {
            return redirect()->route('admin.mesas.index')
                ->with('error', 'No se pudo eliminar la mesa. Error: ' . $e->getMessage());
        }
    }
}
