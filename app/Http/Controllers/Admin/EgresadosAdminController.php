<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Egresado;
use App\Models\Examen;
use App\Repositories\Admin\InscripcionRepository;
use Illuminate\Http\Request;

class EgresadosAdminController extends BaseController
{
    public $defaultFilters = [
        'filter_carrera_id' => 0,
        'filter_alumno_id' => 0,
        'filter_vigente' => 0,
        'filter_finalizada' => 0,
        'filter_ciudad' => 0,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, InscripcionRepository $inscriptosRepo)
    {
        $this->setFilters($request);
        $this->data['inscripciones'] = $inscriptosRepo->index($request);

        $request->flash();

        return view('Admin.Inscriptos.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.Inscriptos.create', [
            'alumnos' => Alumno::orderBy('apellido')->orderBy('nombre')->get(),
            'carreras' => Carrera::where('vigente', '1')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_alumno' => ['required', 'integer'],
            'id_carrera' => ['required', 'integer'],
            'anio_inscripcion' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 10)],
            'indice_libro_matriz' => ['nullable', 'string', 'max:50'],
            'anio_finalizacion' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 10)],
            'estado' => ['required', 'integer'],
        ]);

        try {
            Egresado::create($data);

            if ($request->has('redirect')) {
                return redirect()->to($request->input('redirect'))->with('mensaje', 'Se creó la inscripción');
            } else {
                return redirect()->route('admin.Inscriptos.index')->with('mensaje', 'Se creó la inscripción');
            }

        } catch (\Illuminate\Database\QueryException $e) {
            // Capturar errores específicos de base de datos
            if (str_contains($e->getMessage(), 'Incorrect integer value')) {
                return redirect()->back()
                    ->with('error', 'Los datos ingresados no son válidos. Revisa que los años sean números enteros.')
                    ->withInput();
            }

            // Otros errores de base de datos
            return redirect()->back()
                ->with('error', 'Error al guardar los datos. Revisa la información ingresada.')
                ->withInput();

        } catch (\Exception $e) {
            // Cualquier otro error
            return redirect()->back()
                ->with('error', 'Ocurrió un error inesperado. Intenta nuevamente.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $registro)
    {
        $registro = Egresado::find($registro);
        if (! $registro) {
            return \redirect()->route('admin.Inscriptos.index')->with('aviso', 'La inscripcion no existe');
        }

        return view('Admin.Inscriptos.edit', [
            'registro' => $registro,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $registro)
    {
        $validated = $request->validate([
            'anio_inscripcion' => 'required|integer',
            'indice_libro_matriz' => 'nullable|string',
            'anio_finalizacion' => 'nullable|integer|gte:anio_inscripcion',
            'estado' => 'required|in:0,1,2',
        ], [
            'anio_finalizacion.gte' => 'El año de finalización no puede ser menor que el año de inscripción.',
        ]);

        $registro = Egresado::find($registro);

        if (! $registro) {
            return redirect()->route('admin.Inscriptos.index')->with('aviso', 'La inscripción no existe');
        }

        $registro->update($validated);

        return redirect()->back()->with('mensaje', 'Se actualizó correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $inscripto = Egresado::findOrFail($id);

        // verificar que no tenga mesas futuras
        if (Examen::where('id_alumno', $inscripto->id_alumno)->where('fecha', '>', date('Y-m-d'))->exists()) {
            return redirect()->route('admin.inscriptos.index')
                ->with('error', 'No se pudo eliminar la inscripción porque el alumno tiene mesas de examen futuras.');
        }
        $inscripto->delete();

        return redirect()->route('admin.inscriptos.index')->with([
            'mensaje' => [
                'Se ha eliminado la inscripción',
                'Recuerda que puedes volver a crearla en el apartado "crear inscripción".',
            ],
        ]);
    }
}
