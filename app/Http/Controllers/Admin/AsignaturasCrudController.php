<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CrearAsignaturaRequest;
use App\Http\Requests\EditarAsignaturaRequest;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Repositories\Admin\AsignaturaRepository;
use App\Repositories\Admin\CarreraRepository;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsignaturasCrudController extends BaseController
{
   public $asignaturasRepo;

   public $mensajes = ['mensaje' => [], 'error' => [], 'aviso' => []];

    function __construct(AsignaturaRepository $asignaturasRepo)
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
        $asignaturasList = Asignatura::whereHas('carrera', function($q) use ($carreraId) {
            $q->where('id', $carreraId);
        })->orderBy('nombre')->get();
    } else {
        // Todas las asignaturas
        $asignaturasList = Asignatura::orderBy('nombre')->get();
    }

    // Aplicar filtros al repositorio
    $asignaturas = $this->asignaturasRepo->filter($filters);

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

        Asignatura::create($data);

        return redirect()->back()->with('mensaje', 'Se creo la asignatura');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $asignatura)
    {
        Configuracion::todas();

        $asignatura = Asignatura::with('cursadas.alumno')->find($asignatura);

        return view('Admin.Asignaturas.edit', [
            'asignatura' => $asignatura,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditarAsignaturaRequest $request, Asignatura $asignatura)
    {
        $data = $request->validated();
        $asignatura->update($data);

        if ($request->has('redirect'))
            return redirect()->to($request->input('redirect'))->with('mensaje', 'Se edito la asignatura');
        else
            return redirect()->back()->with('mensaje', 'Se edito la asignatura');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asignatura $asignatura)
    {
        try {
            $asignatura->delete();
            return redirect()->route('admin.asignaturas.index')
                ->with('mensaje', 'Se ha eliminado la asignatura');
        } catch (\Exception $e) {
            return redirect()->route('admin.asignaturas.index')
                ->with('error', 'No se pudo eliminar la asignatura');
        }
    }

    
}
