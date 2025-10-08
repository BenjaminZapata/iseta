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

    public function index(Request $request)
    {
        $this->setFilters($request);
        $request->flash();
        $carreras = $this->carreraRepo->index($request);

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

    public function create()
    {
        return view('Admin.Carreras.create');

    }

    public function store(CrearCarreraRequest $request)
    {
        $data = $request->validated();
        Log::debug($request->file('resolucion_archivo'));
        $data['vigente'] = 1;

        if ($request->hasFile('resolucion_archivo')) {
            $nombre = str_replace(' ', '_', $request->input('nombre')).'.pdf';
            $ruta = $request->file('resolucion_archivo')->storeAs('resoluciones', $nombre, 'public');
            $data['resolucion_archivo'] = 'storage/'.$ruta;
        }

        Carrera::create($data);

        return redirect()->route('admin.carreras.index')->with('mensaje', 'Se creó la carrera correctamente');
    }

    public function show(Carrera $carrera)
    {
        $carrera->load('asignaturas');

        return view('Admin.Carreras.show', ['carrera' => $carrera]);
    }

    public function edit(Carrera $carrera)
    {
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

    public function update(EditarCarreraRequest $request, Carrera $carrera)
    {
        try {
            $datos = $request->validated();

            // / Eliminar archivo
            if ($request->has('eliminar_resolucion_archivo')) {
                if ($carrera->resolucion_archivo) {
                    \Storage::disk('public')->delete(
                        str_replace('storage/', '', $carrera->resolucion_archivo)
                    );
                }
                $datos['resolucion_archivo'] = null;
            }

            // Subir nuevo archivo
            if ($request->hasFile('resolucion_archivo_nuevo')) {
                $nombreArchivo = str_replace(' ', '_', $carrera->nombre).'.pdf';
                $ruta = $request->file('resolucion_archivo_nuevo')
                    ->storeAs('resoluciones', $nombreArchivo, 'public');
                $datos['resolucion_archivo'] = 'storage/'.$ruta;
            }

            $carrera->update($datos);

            return redirect()->back()->with('mensaje', 'Se editó la carrera');
        } catch (\Exception $e) {
            \Log::error($e);

            return redirect()->back()->with('error', 'No se pudo editar la carrera');
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
        $asignaturaData = $request->validated();

        $asignatura = Asignatura::create([
            'nombre' => $asignaturaData['nombre'],
            'tipo_modulo' => $asignaturaData['tipo_modulo'],
            'carga_horaria' => $asignaturaData['carga_horaria'],
            'anio' => $asignaturaData['anio'],
            'observaciones' => $asignaturaData['observaciones'],
        ]);

        try {
            $carrera->asignaturas()->attach([
                'asignatura' => [
                    'id_carrera' => $carrera->id,
                    'id_asignatura' => $asignatura->id,
                    'tipo_modulo' => $asignatura->tipo_modulo,
                    'carga_horaria' => $asignatura->carga_horaria,
                    'anio' => $asignatura->anio,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return redirect()->back()->with('error', 'No se pudo agregar la asignatura');
        }

        return redirect()->back()->with('mensaje', 'Se creó y agregó la asignatura a la carrera');
    }

    public function addAsignaturaView(Request $request)
    {
        $carrera = Carrera::find($request->carrera);
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
            return redirect()->back()->with('error', 'La asignatura ya está en la carrera')->withInput();
        }

        $carrera->asignaturas()->attach($data['id_asignatura'], [
            'id_profesor' => $data['id_profesor'] ?? null,
            'anio' => $data['anio'],
            'carga_horaria' => $data['carga_horaria'],
            'tipo_modulo' => $data['tipo_modulo'] ?? 0,
        ]);

        return redirect()->back()->with('mensaje', 'Se agregó la asignatura a la carrera');
    }

    public function destroy(Carrera $carrera)
    {
        try {
            if ($carrera->inscriptos()->exists()) {
                return redirect()->route('admin.carreras.index')
                    ->with('error', 'No se pudo eliminar la carrera. Tiene alumnos asociados.');
            }

            if ($carrera->mesas()->where('fecha', '>=', now())->exists()) {
                return redirect()->route('admin.carreras.index')
                    ->with('error', 'No se pudo eliminar la carrera. Tiene mesas futuras asociadas.');
            }
            // Verificar si la carrera no contiene el año de finalización
            if (! $carrera->anio_fin) {
                return redirect()->route('admin.carreras.index')
                    ->with('error', 'No se pudo desactivar la carrera. No tiene un año de finalización.');
            }

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
        if ($carrera->inscriptos()->exists()) {
            return redirect()->route('admin.carreras.index')
                ->with('error', 'No se pudo desactivar la carrera. Tiene alumnos asociados.');
        }

        if ($carrera->mesas()->where('fecha', '>=', now())->exists()) {
            return redirect()->route('admin.carreras.index')
                ->with('error', 'No se pudo desactivar la carrera. Tiene mesas futuras asociadas.');
        }

        // Verificar si la carrera no contiene el año de finalización
        if (! $carrera->anio_fin) {
            return redirect()->route('admin.carreras.index')
                ->with('error', 'No se pudo desactivar la carrera. No tiene un año de finalización.');
        }

        $carrera->vigente = false;
        $carrera->anio_fin = now()->year;
        $carrera->save();

        return redirect()->back()->with('success', 'Carrera desactivada correctamente');
    }

    public function reactivar(Carrera $carrera)
    {
        $carrera->vigente = true;
        $carrera->anio_fin = null;
        $carrera->save();

        return redirect()->back()->with('success', 'Carrera reactivada correctamente');
    }
}
