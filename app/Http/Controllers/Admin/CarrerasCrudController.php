<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\AddAsignaturaRequest;
use App\Http\Requests\CrearAsignaturaRequest;
use App\Http\Requests\CrearCarreraRequest;
use App\Http\Requests\EditarCarreraRequest;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Repositories\Admin\CarreraRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarrerasCrudController extends BaseController
{
    public $defaultFilters = [
        'filter_vigente' => 0,
    ];

    public $carreraRepo;

    public function __construct(CarreraRepository $carreraRepo)
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->carreraRepo = $carreraRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->setFilters($request);

        $request->flash();
        $carreras = $this->carreraRepo->index($request);

        // Obtener años disponibles para cada carrera
        $aniosPorCarrera = [];

        foreach ($carreras as $carrera) {
            $anios = \DB::table('cursadas')
                ->where('id_carrera', $carrera->id)
                ->whereNotNull('anio_cursada')
                ->distinct()
                ->orderByDesc('anio_cursada')
                ->pluck('anio_cursada')
                ->toArray();

            $aniosPorCarrera[$carrera->id] = $anios;
        }

        $this->data['carreras'] = $carreras;
        $this->data['aniosPorCarrera'] = $aniosPorCarrera;
      $filterVigente = $request->input('filter_vigente', '');



        return view('Admin.Carreras.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.Carreras.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearCarreraRequest $request)
    {
        $data = $request->validated();

        $request->validate([
            'resolucion_archivo' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $data['vigente'] = 1;

        if ($request->hasFile('resolucion_archivo')) {
            $nombre = str_replace(' ', '_', $request->input('nombre')).'.pdf';
            $ruta = $request->file('resolucion_archivo')->storeAs('resoluciones', $nombre, 'public');
            $data['resolucion_archivo'] = 'storage/'.$ruta;

        }

        Carrera::create($data);

        return redirect()->route('admin.carreras.index');
    }

    public function show(Carrera $carrera)
    {
        $carrera->load('asignaturas');

        return view('Admin.Carreras.show', ['carrera' => $carrera]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carrera $carrera)
    {
        // Obtener los años disponibles de cursadas para esta carrera
        $anios = $carrera->cursadas()
            ->whereNotNull('anio_cursada')
            ->distinct()
            ->orderByDesc('anio_cursada')
            ->pluck('anio_cursada')
            ->toArray();

        return view('Admin.Carreras.edit', [
            'carrera' => $carrera,
            'aniosPorCarrera' => [$carrera->id => $anios],
            'method' => 'put',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Carrera $carrera)
{
    try {
        // Validar todos los campos relevantes
        $validated = $request->validate([
            'resolucion_archivo_nuevo' => 'nullable|file|mimes:pdf|max:20480',
            'eliminar_resolucion_archivo' => 'nullable|boolean',
            // Agregá aquí cualquier otro campo que uses, por ejemplo:
            'nombre' => 'sometimes|string|max:255',
            // ...
        ]);

        // Eliminar archivo si se solicita
        if ($request->has('eliminar_resolucion_archivo')) {
            if ($carrera->resolucion_archivo && file_exists(public_path($carrera->resolucion_archivo))) {
                unlink(public_path($carrera->resolucion_archivo));
            }
            $validated['resolucion_archivo'] = null;
        }

        // Subir nuevo archivo si se proporciona
        if ($request->hasFile('resolucion_archivo_nuevo')) {
            $archivo = $request->file('resolucion_archivo_nuevo');
            $nombreArchivo = 'resolucion_' . uniqid() . '.pdf';
            $ruta = $archivo->storeAs('resoluciones', $nombreArchivo, 'public');
            $validated['resolucion_archivo'] = 'storage/' . $ruta;
        }

        // Actualizar carrera
        $carrera->update($validated);

        return $request->has('redirect')
            ? redirect()->to($request->input('redirect'))->with('mensaje', 'Se editó la carrera')
            : redirect()->back()->with('mensaje', 'Se editó la carrera');

    } catch (\Exception $e) {
        Log::error($e);
        return redirect()->back()->with('error', 'No se pudo editar la carrera'. $e->getMessage())->withInput();
    }
}


    public function createAsignaturaView(Carrera $carrera)
    {
        return view('Admin.Carreras.create_asignatura', [
            'carrera' => $carrera,
        ]);
    }

    public function createAsignatura(CrearAsignaturaRequest $request, Carrera $carrera)
    {
        $asignatura = $request->validated();
        $asignatura = Asignatura::create([
            'nombre' => $asignatura['nombre'],
            'tipo_modulo' => $asignatura['tipo_modulo'],
            'carga_horaria' => $asignatura['carga_horaria'],
            'anio' => $asignatura['anio'],
            'observaciones' => $asignatura['observaciones'],
        ]);
        log::debug($asignatura);
        try {
            $data = [
                'id_carrera' => $carrera->id,
                'id_asignatura' => $asignatura['id'],
                'tipo_modulo' => $asignatura['tipo_modulo'],
                'carga_horaria' => $asignatura['carga_horaria'],
                'anio' => $asignatura['anio'],
            ];
            log::debug($data);
            $carrera->asignaturas()->attach(['asignatura' => $data]);
        } catch (\Exception $e) {
            Log::error($e);

            return redirect()->back()->with('error', 'No se pudo agregar la asignatura');
        }

        return redirect()->back()->with('mensaje', 'Se creo y agrego la asignatura a la carrera');
    }

    public function addAsignaturaView(Request $request)
    {
        log::debug($request->all());
        $carrera = Carrera::find($request->carrera);
        Log::debug($carrera);
        $asignaturas = Asignatura::orderBy('nombre')->get();

        return view('Admin.Carreras.add_asignatura', [
            'carrera' => $carrera,
            'asignaturas' => $asignaturas,
        ]);
    }

    public function addAsignatura(AddAsignaturaRequest $request, Carrera $carrera)
    {
        $data = $request->validated();
        if ($carrera->asignaturas()->where('id_asignatura', $data['id_asignatura'])->exists()) {
            return redirect()->back()->with('error', 'La asignatura ya está en la carrera')->withInput();
        }
        $carrera->asignaturas()->attach($data['id_asignatura'], [
    'id_profesor' => $data['id_profesor'] ?? 0,
    'anio' => $data['anio'],
    'carga_horaria' => $data['carga_horaria'],
    'tipo_modulo' => $data['tipo_modulo'] ?? 0
]);

        return redirect()->back()->with('mensaje', 'Se agrego la asignatura a la carrera');
    }

    public function destroy(Carrera $carrera)
    {
        try {
            // verificar si contiene inscriptos
            if ($carrera->inscriptos()->exists()) {
                return redirect()->route('admin.carreras.index')
                    ->with('error', 'No se pudo eliminar la carrera. Tiene alumnos asociados.');
            }
            // verificar si contiene alumnos en mesas futuras
            if ($carrera->mesas()->where('fecha', '>=', now())->exists()) {
                return redirect()->route('admin.carreras.index')
                    ->with('error', 'No se pudo eliminar la carrera. Tiene mesas futuras asociadas.');
            }
            // Verificar si la carrera no contiene el año de finalización
            if (! $carrera->anio_fin) {
                return redirect()->route('admin.carreras.index')
                    ->with('error', 'No se pudo Desactivar la carrera. No tiene un año de finalización.');
            }

            // Ahora eliminar la carrera
            $carrera->delete();

            return redirect()->route('admin.carreras.index')
                ->with('success', 'Carrera eliminada correctamente');
        } catch (\Throwable $e) {
            return redirect()->route('admin.carreras.index')
                ->with('error', 'No se pudo eliminar la carrera. Verifique que no tenga relaciones asociadas.');
        }
    }

    public function desactivar(Carrera $carrera)
    {
        // verificar si contiene inscriptos
        if ($carrera->inscriptos()->exists()) {
            return redirect()->route('admin.carreras.index')
                ->with('error', 'No se pudo Desactivar la carrera. Tiene alumnos asociados.');
        }
        // verificar si contiene alumnos en mesas futuras
        if ($carrera->mesas()->where('fecha', '>=', now())->exists()) {
            return redirect()->route('admin.carreras.index')
                ->with('error', 'No se pudo Desactivar la carrera. Tiene mesas futuras asociadas.');
        }

        // Verificar si la carrera no contiene el año de finalización
        if (! $carrera->anio_fin) {
            return redirect()->route('admin.carreras.index')
                ->with('error', 'No se pudo Desactivar la carrera. No tiene un año de finalización.');
        }

        $carrera->vigente = false;
        $carrera->anio_fin = now()->year;
        $carrera->save();

        return redirect()->back()
            ->with('success', 'Carrera desactivada correctamente');
    }

    public function reactivar(Carrera $carrera)
    {
        $carrera->vigente = true;
        $carrera->anio_fin = null;
        $carrera->save();

        return redirect()->back()
            ->with('success', 'Carrera reactivada correctamente');
    }

    public function deleteAsignatura(Request $request, Carrera $carrera, Asignatura $asignatura)
    {
        $carrera->asignaturas()->detach($asignatura);

        return redirect()->back()->with('mensaje', 'Se elimino la asignatura');
    }
}
