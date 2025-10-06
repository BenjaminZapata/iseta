<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\crearProfesorRequest;
use App\Http\Requests\EditarProfesorRequest;
use App\Mail\ProfesorCreado;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Mesa;
use App\Models\Profesor;
use App\Repositories\Admin\ProfesorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Log;

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
        $carreras = Carrera::with('asignaturas')->get();

        return view('Admin.Profesores.create', compact('carreras'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(crearProfesorRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Str::password();

        try {
            Mail::to($data['email'])->queue(new ProfesorCreado($data));
        } catch (\Throwable $th) {
            Log::error($th);
        }
        $data['password'] = Hash::make($data['password']);
        return redirect()->route('admin.profesores.index')->with('mensaje', 'Se creó el profesor');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profesor $profesor, Carrera $carreras)
    {
        $mesas = Mesa::where(function ($query) use ($profesor) {
            $query->where('prof_presidente', $profesor->id)
                ->orWhere('prof_vocal_1', $profesor->id)
                ->orWhere('prof_vocal_2', $profesor->id);
        })
            ->whereRaw('fecha > NOW()')
            ->get();
        $carreras = Carrera::with('asignaturas')->get();

        return view('Admin.Profesores.edit', [
            'profesor' => $profesor,
            'mesas' => $mesas,
            'carreras' => $carreras,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
 public function update(EditarProfesorRequest $request, Profesor $profesor)
{
    try {
        // Actualiza los datos del profesor
        $profesor->update($request->validated());

        return redirect()->route('admin.profesores.index')
            ->with('mensaje', 'Se editó el profesor correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
        Log::error("Error al actualizar profesor: " . $e->getMessage());

        preg_match("/for column '(\w+)'/", $e->getMessage(), $matches);
        $campo = $matches[1] ?? 'desconocido';

        return redirect()->back()
            ->withInput()
            ->with('error', "El campo '{$campo}' tiene demasiados caracteres para la base de datos.");
    } catch (\Throwable $e) {
        Log::error("Error inesperado: " . $e->getMessage());

        return redirect()->back()
            ->withInput()
            ->with('error', 'Ocurrió un error inesperado al actualizar el profesor.');
    }
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profesor $profesor)
    {
        try {
            if ($profesor->profesor_mesa()->exists() || $profesor->profesor_mesa_vocal()->exists() || $profesor->profesor_mesa_vocal2()->exists()) {
                return redirect()->route('admin.profesores.index')
                    ->with('error', 'No se pudo eliminar el Profesor. Tiene mesas asignadas.');
            }
            // verificar si el profesor tiene asignaturas asignadas en la tabla pivote
            if ($profesor->asignaturas()->where('id_profesor', $profesor->id)->exists()) {
                return redirect()->route('admin.profesores.index')
                    ->with('error', 'No se pudo eliminar el Profesor. Tiene asignaturas asignadas.');
            }

            $profesor->delete();

            return redirect()->route('admin.profesores.index')
                ->with('mensaje', 'Se ha eliminado el Profesor.');
        } catch (\Exception $e) {
            return redirect()->route('admin.profesores.index')
                ->with('error', 'No se pudo eliminar el Profesor. ' . $e->getMessage());
        }
    }
   public function vincularAsignaturas(Request $request, Profesor $profesor)
{
    try {
        $seleccionadas = json_decode($request->input('asignaturas_payload'), true);

        if (empty($seleccionadas)) {
            return redirect()->back()->with('error', ' No hay asignaturas para asignar ni quitar.');
        }

        $totalVinculadas = 0;
        $totalDesvinculadas = 0;

        foreach ($seleccionadas as $idCarrera => $acciones) {
            // Vincular
            if (!empty($acciones['vincular'])) {
                foreach ($acciones['vincular'] as $idAsignatura) {
                    $asignatura = Asignatura::find($idAsignatura);
                    if ($asignatura) {
                        $asignatura->carrera()->updateExistingPivot($idCarrera, [
                            'id_profesor' => $profesor->id,
                        ]);
                        $totalVinculadas++;
                    }
                }
            }

            // Desvincular
            if (!empty($acciones['desvincular'])) {
                foreach ($acciones['desvincular'] as $idAsignatura) {
                    $asignatura = Asignatura::find($idAsignatura);
                    if ($asignatura) {
                        $asignatura->carrera()->updateExistingPivot($idCarrera, [
                            'id_profesor' => null,
                        ]);
                        $totalDesvinculadas++;
                    }
                }
            }
        }

        if ($totalVinculadas === 0 && $totalDesvinculadas === 0) {
            return redirect()->back()->with('error', ' No se realizaron cambios en las asignaturas.');
        }

        $mensaje = ' ';
        if ($totalVinculadas > 0) $mensaje .= "$totalVinculadas asignatura(s) vinculada(s). ";
        if ($totalDesvinculadas > 0) $mensaje .= "$totalDesvinculadas asignatura(s) desvinculada(s).";

        return redirect()->back()->with('mensaje', $mensaje);
    } catch (\Throwable $e) {
        \Log::error("Error al vincular/desvincular asignaturas: " . $e->getMessage());
        return redirect()->back()->with('error', ' Hubo un problema al procesar las asignaciones.');
    }
}
}
