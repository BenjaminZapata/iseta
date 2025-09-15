<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Http\Controllers\BaseController;
use App\Http\Requests\crearAlumnoRequest;
use App\Http\Requests\EditarAlumnoRequest;
use App\Models\Alumno;
use App\Models\Cursada;
use App\Models\Examen;
use App\Repositories\Admin\AlumnoRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\view\WithInput;

class AlumnoCrudController extends BaseController
{
    public $alumnosRepo;
    public $defaultFilters = [
        'filter_carrera_id' => 0,
        'filter_ciudad' => 0,
        'filter_estado_civil' => 0
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
        return view('Admin.Alumnos.create');
    }

    /**
     * Guarda un nuevo alumno creado
     */
    public function store(crearAlumnoRequest $request)
    {
        $data = $request->validated();
        $response = redirect()->back();

        if (Alumno::where('dni', strtolower($data['dni']))->first()) {
            return $response->with('aviso', 'Ya hay un usuario con ese numero de documento')->withInput();
        } else {
            Alumno::create($data);
            return $response->with('mensaje', 'Se creo el alumno');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Alumno $alumno)
    {
        $cursadas = Cursada::select(
            'asignaturas.nombre as asignatura',
            'cursadas.aprobada',
            'cursadas.condicion',
            'cursadas.anio_cursada',
            'cursadas.id',
            'carreras.nombre as carrera',
            'asignaturas.anio as anio_asig'
        )
            ->join('asignaturas', 'cursadas.id_asignatura', 'asignaturas.id')
            ->join('carrera_asignatura_profesor as cap', 'asignaturas.id', 'cap.id_asignatura')
            ->join('carreras', 'cap.id_carrera', 'carreras.id')
            ->where('cursadas.id_alumno', $alumno->id)
            ->orderBy('carreras.id')
            ->orderBy('asignaturas.anio')
            ->orderBy('asignaturas.id')
            ->orderBy('cursadas.anio_cursada')
            ->get();

        $examenes = Examen::select('examenes.fecha', 'asignaturas.nombre as asignatura', 'examenes.nota', 'examenes.id', 'carreras.nombre as carrera', 'asignaturas.anio as anio_asig')
            ->join('asignaturas', 'examenes.id_asignatura', 'asignaturas.id')
            ->join('carrera_asignatura_profesor as cap', 'asignaturas.id', 'cap.id_asignatura')
            ->join('carreras', 'cap.id_carrera', 'carreras.id')
            ->where('examenes.id_alumno', $alumno->id)
            ->orderBy('carreras.id')
            ->orderBy('asignaturas.anio')
            ->orderBy('examenes.fecha', 'desc')
            ->get();

        return view('Admin.Alumnos.edit', [
            'alumno' => $alumno,
            'cursadas' => $cursadas,
            'examenes' => $examenes,
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

        if ($data['dni'] && Alumno::where('id', '!=', $alumno->id)->where('dni', $data['dni'])->exists()) {
            $mensajes['aviso'][] = 'Ya hay un usuario con ese numero de dni';
        }

        $alumno->update($data);
        $mensajes['mensaje'][] = 'Se actualizo el alumno';
        
        return redirect()->back()->with('mensajes', $mensajes);
        
    } catch (\Illuminate\Database\QueryException $e) {
        $mensajes = ['aviso' => [], 'error' => [], 'mensaje' => []];
        
        if (str_contains($e->getMessage(), 'Incorrect integer value')) {
            if (str_contains($e->getMessage(), 'becas')) {
                $mensajes['error'][] = 'El campo de becas debe ser un número entero o dejarse vacío.';
            } else {
                $mensajes['error'][] = 'Uno de los campos numéricos contiene un valor inválido.';
            }
        } else {
            $mensajes['error'][] = 'Error al actualizar los datos del alumno.';
        }
        
        return redirect()->back()->with('mensajes', $mensajes)->withInput();
        
    } catch (\Exception $e) {
        $mensajes = ['aviso' => [], 'error' => [], 'mensaje' => []];
        $mensajes['error'][] = 'Ocurrió un error inesperado. Intenta nuevamente.';
        
        return redirect()->back()->with('mensajes', $mensajes)->withInput();
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumno $alumno)
    {
        try {

            //verificar si tiene cursadas pero con el estado 
            if($alumno->cursadas()->exists()) {
                return redirect()->route('admin.alumnos.index')
                    ->with('error', 'No se pudo eliminar el alumno porque tiene cursadas asociadas.');
            }

            //verificar si tiene mesas futuras
            if(Examen::where('id_alumno',$alumno->id)->where('fecha','>',date('Y-m-d'))->exists()) {
                return redirect()->route('admin.alumnos.index')
                    ->with('error', 'No se pudo eliminar el alumno porque tiene mesas de examen futuras.');
            }
            

            //verificar si esta inscripto en alguna carrera pero con el estado regular
            if ($alumno->carreras()->where('estado', 'regular')->exists()) {
    return redirect()->route('admin.alumnos.index')
        ->with('error', 'No se pudo eliminar el alumno porque está inscripto en una o más carreras como regular');
}


            //eliminar alumno
            $alumno->delete();
            return redirect()->route('admin.alumnos.index')
                ->with('mensaje', 'Se ha eliminado el alumno');
        } catch (\Exception $e) {
            return redirect()->route('admin.alumnos.index')
                ->with('error', 'No se pudo eliminar el alumno.');
        }
    }



    public function verificar(Request $request, Alumno $alumno)
    {

        if (1 != $alumno->verificado) {
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
