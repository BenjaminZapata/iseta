<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CrearCarreraRequest;
use App\Http\Requests\EditarCarreraRequest;
use App\Http\Requests\CrearAsignaturaRequest;
use App\Models\Carrera;
use App\Models\Asignatura;
use App\Repositories\Admin\CarreraRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\Alumno;

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
        $nombre = str_replace(' ', '_', $request->input('nombre')) . '.pdf';
        $ruta = $request->file('resolucion_archivo')->storeAs('resoluciones', $nombre, 'public');
        $data['resolucion_archivo'] = 'storage/' . $ruta;
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
    public function update(EditarCarreraRequest $request, Carrera $carrera)
    {
        try
        {
            $datos = $request->validated();

            $carrera->update($datos);

            if ($request->has('redirect')){
                return redirect()->to($request->input('redirect'))->with('mensaje', 'Se edito la carrera');
            }else{
                return redirect()->back()->with('mensaje', 'Se edito la carrera');
            }
        }catch(\Exception $e){
            Log::error($e);
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
            $carrera->asignaturas()->attach(["asignatura" => $data]);
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
        $asignaturas = Asignatura::orderBy('nombre')->get();
        $id_asignatura = $request->id_asignatura ?? null;
        return view('Admin.Carreras.add_asignatura', [
            'carrera' => $carrera,
            'asignaturas' => $asignaturas,
            'id_asignatura' => $id_asignatura,
        ]);
    }
    public function addAsignatura(Request $request, Carrera $carrera)
    {
        try {
            $data = [
                'id_carrera' => $request->carrera_id,
                'id_asignatura' => $request->asignatura_id,
                'anio' => $request->anio,
                'tipo_modulo' => $request->tipo_modulo,
                'carga_horaria' => $request->carga_horaria,
            ];
            log::debug($data);
            $carrera->asignaturas()->attach(["asignatura" => $data]);
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'No se pudo agregar la asignatura');
        }

        return redirect()->back()->with('mensaje', 'Se agrego la asignatura a la carrera');
    }

    public function destroy(Carrera $carrera)
    {
        try {
            //verificar si contiene inscriptos
            if ($carrera->inscriptos()->exists()) {
                return redirect()->route('admin.carreras.index')
                    ->with('error', 'No se pudo eliminar la carrera. Tiene alumnos asociados.');
            }
            //verificar si contiene alumnos en mesas futuras
            if ($carrera->mesas()->where('fecha', '>=', now())->exists()) {
                return redirect()->route('admin.carreras.index')
                    ->with('error', 'No se pudo eliminar la carrera. Tiene mesas futuras asociadas.');
            }
             // Verificar si la carrera no contiene el año de finalización
            if (!$carrera->anio_fin) {
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
            //verificar si contiene inscriptos
            if ($carrera->inscriptos()->exists()) {
                return redirect()->route('admin.carreras.index')
                    ->with('error', 'No se pudo Desactivar la carrera. Tiene alumnos asociados.');
            }
            //verificar si contiene alumnos en mesas futuras
            if ($carrera->mesas()->where('fecha', '>=', now())->exists()) {
                return redirect()->route('admin.carreras.index')
                    ->with('error', 'No se pudo Desactivar la carrera. Tiene mesas futuras asociadas.');
            }

             // Verificar si la carrera no contiene el año de finalización
            if (!$carrera->anio_fin) {
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