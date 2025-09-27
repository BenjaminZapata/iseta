<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\crearProfesorRequest;
use App\Http\Requests\EditarProfesorRequest;
use App\Models\Configuracion;
use App\Models\Mesa;
use App\Models\Profesor;
use App\Repositories\Admin\ProfesorRepository;
use Illuminate\Http\Request;

class ProfesoresCrudController extends BaseController
{
    public $profeRepo;

    public function __construct(ProfesorRepository $profeRepo)
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->profeRepo = $profeRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $config = Configuracion::todas();
        $this->setFilters($request);

        $this->data['profesores'] = $this->profeRepo->index($request);

        return view('Admin.Profesores.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.Profesores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(crearProfesorRequest $request)
    {
        $data = $request->validated();

        Profesor::create($data);

        return redirect()->route('admin.profesores.index')->with('mensaje', 'Se creo el profesor');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profesor $profesor)
    {
        $mesas = Mesa::where(function ($query) use ($profesor) {
            $query->where('prof_presidente', $profesor->id)
                ->orWhere('prof_vocal_1', $profesor->id)
                ->orWhere('prof_vocal_2', $profesor->id);
        })
            ->whereRaw('fecha > NOW()')
            ->get();

        return view('Admin.Profesores.edit', [
            'profesor' => $profesor,
            'mesas' => $mesas,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditarProfesorRequest $request, Profesor $profesor)
    {
        try {
            $profesor->update($request->validated());

            return redirect()->route('admin.profesores.index')
                ->with('mensaje', 'Se editó el profesor correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Extraer el campo que dio error del mensaje
            preg_match("/for column '(\w+)'/", $e->getMessage(), $matches);
            $campo = $matches[1] ?? 'desconocido';

            return redirect()->back()
                ->withInput()
                ->with('error', "El campo '{$campo}' tiene demasiados caracteres para la base de datos.");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profesor $profesor)
    {
        try {
            if (! empty($profesor->profesor_mesa()->first())) {
                return redirect()->route('admin.profesores.index')
                    ->with('error', 'No se pudo eliminar el Profesor. Tiene mesas asignadas.');
            } elseif (! empty($profesor->profesor_mesa_vocal()->first())) {
                return redirect()->route('admin.profesores.index')
                    ->with('error', 'No se pudo eliminar el Profesor. Tiene mesas asignadas.');

            } elseif (! empty($profesor->profesor_mesa_vocal2()->first())) {
                return redirect()->route('admin.profesores.index')
                    ->with('error', 'No se pudo eliminar el Profesor. Tiene mesas asignadas.');
            }

            // verificar si el profesor tiene asignaturas asignadas en la tabla pivote
            if (! empty($profesor->asignaturas()->where('id_profesor', $profesor->id)->first())) {
                return redirect()->route('admin.profesores.index')
                    ->with('error', 'No se pudo eliminar el Profesor. Tiene asignaturas asignadas.');
            }

            $profesor->delete();

            return redirect()->route('admin.profesores.index')
                ->with('mensaje', 'Se ha eliminado el Profesor.');
        } catch (\Exception $e) {
            return redirect()->route('admin.profesores.index')
                ->with('error', 'No se pudo eliminar el Profesor. '.$e->getMessage());
        }
    }
}
