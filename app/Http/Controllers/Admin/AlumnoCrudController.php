<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\crearAlumnoRequest;
use App\Http\Requests\EditarAlumnoRequest;
use App\Models\Alumno;
use App\Repositories\Admin\AlumnoRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumnoCrudController extends BaseController
{
    public $alumnosRepo;

    public $defaultFilters = [
        'filter_carrera_id' => 0,
        'filter_ciudad' => 0,
        'filter_estado_civil' => 0,
    ];

    public $mensajes = ['mensaje' => [], 'error' => [], 'aviso' => []];

    public function __construct(AlumnoRepository $alumnosRepo)
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->alumnosRepo = $alumnosRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $this->setFilters($request);
        $request->flash();
        $this->data['alumnos'] = $this->alumnosRepo->index($request);

        return view('Admin.Alumnos.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('Admin.Alumnos.create', [
            'carreras' => \App\Models\Carrera::orderBy('nombre')->where('vigente', '1')->get(),
        ]);
    }

    /**
     * Guarda un nuevo alumno creado
     */
    public function store(crearAlumnoRequest $request)
    {
        $data = $request->validated();

        $response = flash()->back();

        return $response->with('mensaje', 'Se creo el alumno');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Alumno $alumno)
    {
        $alumno = $alumno->load(['carreras', 'cursadas', 'examenes']);

        return view('Admin.Alumnos.edit', [
            'alumno' => $alumno,
            'carreras' => $alumno->carrerasIncriptas(),
            'esAlumno' => true,
            'method' => 'put',

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditarAlumnoRequest $request, Alumno $alumno)
    {
        try {
            $data = $request->validated();

            $mensajes = ['aviso' => [], 'error' => [], 'mensaje' => []];

            $alumno->update($data);
            $mensajes['mensaje'][] = 'Se actualizo el alumno';

            return redirect()->back()->with('mensajes', $mensajes);
        } catch (\Illuminate\Database\QueryException $e) {
            $mensajes = ['aviso' => [], 'error' => [], 'mensaje' => []];
            $mensajes['error'][] = 'Error al actualizar los datos del alumno.';

            return redirect()->back()->with('mensajes', $mensajes)->withInput();
        } catch (\Exception $e) {
            $mensajes = ['aviso' => [], 'error' => [], 'mensaje' => []];
            $mensajes['error'][] = 'Ocurrió un error inesperado. Intenta nuevamente.';

            return redirect()->back()->with('mensajes', $mensajes)->withInput();
        }
    }

    public function cambiarEstadoInscripcion(Request $request, Alumno $alumno, int $id_carrera)
    {
        if ($request->estados[$id_carrera] != 0 && $request->estados[$id_carrera] != 1 && $request->estados[$id_carrera] != 2) {
            return redirect()->route('admin.alumnos.edit', $alumno)->with('error', 'No se pudo actualizar el estado de inscripcion');
        }
        $inscripto = $alumno->egresadoinscripto()->where('id_carrera', $id_carrera)->first();
        $inscripto->estado = $request->estados[$id_carrera];
        $inscripto->update();

        return redirect()->route('admin.alumnos.edit', $alumno)->with('mensaje', 'Se actualizo el estado de inscripcion');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumno $alumno)
    {
        try {

            // verificar si tiene cursadas pero con el estado
            if ($alumno->cursadas()->exists()) {
                return redirect()->route('admin.alumnos.index')
                    ->with('error', 'No se pudo eliminar el alumno porque tiene cursadas asociadas.');
            }

            // verificar si tiene mesas futuras
            if ($alumno->examenes()->exists()) {
                return redirect()->route('admin.alumnos.index')
                    ->with('error', 'No se pudo eliminar el alumno porque tiene mesas de examen futuras.');
            }

            // verificar si esta inscripto en alguna carrera pero con el estado regular
            if ($alumno->carreras()->exists()) {
                return redirect()->route('admin.alumnos.index')
                    ->with('error', 'No se pudo eliminar el alumno porque está inscripto en una o más carreras');
            }

            // eliminar alumno
            $alumno->delete();

            return redirect()->route('admin.alumnos.index')
                ->with('mensaje', 'Se ha eliminado el alumno');
        } catch (\Exception $e) {
            return redirect()->route('admin.alumnos.index')
                ->with('error', 'No se pudo eliminar el alumno.');
        }
    }

    public function softDelete(Alumno $alumno)
    {
        try {
            $alumno->estado = 1;
            $alumno->save();

            return redirect()->route('admin.alumnos.index')
                ->with('mensaje', 'Se ha inhabilitado el alumno');
        } catch (\Exception $e) {
            return redirect()->route('admin.alumnos.index')
                ->with('error', 'No se pudo inhabilitar el alumno.');
        }
    }

    public function verificar(Request $request, Alumno $alumno)
    {

        if ($alumno->verificado != 1) {
            $alumno->verificar();
            $this->mensajes['mensaje'][] = 'Se ha verificado al alumno';
        }

        if ($alumno->password == 0) {
            $alumno->password = bcrypt($alumno->dni);
            $alumno->save();
            $this->mensajes['mensaje'][] = 'Se utilizará su dni como clave de acceso';
        }

        // dd($this->mensajes,['mensaje'=>['Se ha verificado al alumno','Se utilizará su dni como clave de acceso']]);
        return redirect()->route('admin.alumnos.index')->with('mensajes', $this->mensajes);
    }
}
