<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CrearAsignaturaRequest;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Repositories\Admin\AsignaturaRepository;
use Illuminate\Http\Request;

class AsignaturasCrudController extends BaseController
{
    public $asignaturasRepo;

    public $mensajes = ['mensaje' => [], 'error' => [], 'aviso' => []];

    public function __construct(AsignaturaRepository $asignaturasRepo)
    {
        $this->middleware('auth:admin');
        $this->asignaturasRepo = $asignaturasRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $carreraId = $filters['filter_carrera_id'] ?? null;

        // Asignaturas para el dropdown del select
        if ($carreraId && $carreraId != 0) {
            // Solo las asignaturas de esa carrera
            $asignaturasList = Asignatura::whereHas('carrera', function ($q) use ($carreraId) {
                $q->where('id', $carreraId);
            })->orderBy('nombre')->get();
        } else {
            // Todas las asignaturas
            $asignaturasList = Asignatura::orderBy('nombre')->get();
        }

        // Aplicar filtros al repositorio
        $asignaturas = $this->asignaturasRepo->filter($filters);
        $request->flash();

        return view('Admin.Asignaturas.index', [
            'filters' => $filters,
            'asignaturas' => $asignaturas,
            'asignaturasList' => $asignaturasList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('Admin.Asignaturas.create', [
            'asignatura' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearAsignaturaRequest $request)
    {
        $data = $request->validated();

        // Verificar unicidad contextual
        $existe = Asignatura::where('nombre', $data['nombre'])
            ->where('carga_horaria', $data['carga_horaria'])
            ->where('anio', 0)// dejar carga_horaria en estos datos aunque se usen "cantidad de modulos" ya que se usa "carga_horaria" en la base de datos
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withErrors(['nombre' => 'Ya existe una asignatura con ese nombre y cantidad de módulos'])
                ->withInput();
        }

        // Generar clave única
        $data['clave_unica'] = \Str::uuid(); // o lógica propia

        Asignatura::create($data);

        return redirect()->back()->with('mensaje', 'Se creó la asignatura correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asignatura $asignatura)
    {
        try {

            // Verificar si la asignatura está vinculada a alguna carrera vigente
            $carrerasVigentes = $asignatura->carrera->filter(fn($c) => $c->vigente);

            if ($carrerasVigentes->isNotEmpty()) {
                return redirect()->back()->with('error', 'No se pudo eliminar la asignatura porque está vinculada a una carrera vigente.');
            }

            // Verificar si la asignatura tiene relaciones con cursadas
            if ($asignatura->cursadas()->exists()) {
            return redirect()->back()->with('error', 'No se pudo eliminar la asignatura porque tiene cursadas asociadas.');
            }

            // Verificar si la asignatura tiene relaciones con materias correlativas
            if ($asignatura->correlativasReverse()->exists()) {
            return redirect()->back()->with('error', 'No se pudo eliminar la asignatura porque tiene materias correlativas asociadas.');
            }

            // Verificar si la asignatura tiene relaciones con mesas
            if ($asignatura->mesas()->exists()) {
            return redirect()->back()
            ->with('error', 'No se pudo eliminar la asignatura porque tiene mesas asociadas.');
            }

            // Si no tiene relaciones bloqueantes, procedemos a eliminarla
            $asignatura->delete();

            return redirect()->back()
            ->with('mensaje', 'Se ha eliminado la asignatura');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo eliminar la asignatura');
        }
    }
}