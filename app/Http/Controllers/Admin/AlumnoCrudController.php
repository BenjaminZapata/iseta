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
use stdClass;

class AlumnoCrudController extends BaseController
{
    public $alumnosRepo;
    public $defaultFilters = [
        'filter_carrera_id' => 0,
        'filter_ciudad' => 0,
        'filter_estado_civil' => 0
    ];
    public $mensajes = ['mensaje'=>[],'error'=>[],'aviso'=>[]];

    public function __construct(AlumnoRepository $alumnosRepo) {
        parent::__construct();
        $this->alumnosRepo = $alumnosRepo;
    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $this->setFilters($request);
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

        if(Alumno::where('dni', strtolower($data['dni']))->first()){
            return $response -> with('aviso','Ya hay un usuario con ese numero de documento')->withInput();
        } else {
            Alumno::create($data);
            return $response->with('mensaje','Se creo el alumno');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Alumno $alumno)
    {
        $cursadas = Cursada::select(
                'asignaturas.nombre as asignatura',
                'cursadas.aprobada' ,
                'cursadas.condicion' ,
                'cursadas.anio_cursada' ,
                'cursadas.id' ,
                'carreras.nombre as carrera',
                'asignaturas.anio as anio_asig'
            )
            ->join('asignaturas', 'cursadas.id_asignatura','asignaturas.id')
            ->join('carrera_asignatura_profesor as cap','asignaturas.id','cap.id_asignatura')
            ->join('carreras','cap.id_carrera','carreras.id')
            -> where('cursadas.id_alumno',$alumno->id)
            -> orderBy('carreras.id')
            -> orderBy('asignaturas.anio')
            -> orderBy('asignaturas.id')
            -> orderBy('cursadas.anio_cursada')
            -> get();

            $examenes = Examen::select('examenes.fecha','asignaturas.nombre as asignatura', 'examenes.nota' ,'examenes.id' ,'carreras.nombre as carrera','asignaturas.anio as anio_asig')
            ->join('asignaturas', 'examenes.id_asignatura','asignaturas.id')
            -> join('carrera_asignatura_profesor as cap','asignaturas.id','cap.id_asignatura')
            -> join('carreras', 'cap.id_carrera','carreras.id')
            -> where('examenes.id_alumno',$alumno->id)
            -> orderBy('carreras.id')
            -> orderBy('asignaturas.anio')
            -> orderBy('examenes.fecha','desc')
            -> get();

        return view('Admin.Alumnos.edit', [
            'alumno' => $alumno,
            'cursadas' => $cursadas,
            'examenes' => $examenes,
            'carreras' => $alumno->carrerasIncriptas(),
            'esAlumno' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditarAlumnoRequest $request, Alumno $alumno): RedirectResponse
    {
        $data = $request->validated();

        $mensajes = ['aviso'=>[],'error'=>[],'mensaje'=>[]];

        if($data['telefono1'] && Alumno::where('id','!=',$alumno->id)->where('telefono1', strtolower($data['telefono1']))->exists()){
            $mensajes['aviso'][] = 'Ya hay un usuario con ese numero de telefono';
        };

        if($data['email'] && Alumno::where('email',strtolower($data['email'])) -> where('id','<>',$alumno->id)->exists() ){
            return \redirect()->back()->with('error','El mail ya esta en usa')->withInput();
        }

        $alumno->update($data);
        $mensajes['mensaje'][] = 'Se actualizo el alumno';
        return redirect()->back()->with('mensajes', $mensajes);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumno $alumno)
    {

        $alumno->delete();
        return redirect() -> route('admin.alumnos.index') -> with('mensaje', 'Se ha eliminado el alumno');
    }


    public function verificar(Request $request, Alumno $alumno){

        if( 1 != $alumno->verificado){
            $alumno->verificar();
            $this->mensajes['mensaje'][] = 'Se ha verificado al alumno';
        }

        if($alumno->password == 0){
            $alumno->password = bcrypt($alumno->dni);
            $alumno->save();
            $this->mensajes['mensaje'][] = 'Se utilizará su dni como clave de acceso';
        }
        // dd($this->mensajes,['mensaje'=>['Se ha verificado al alumno','Se utilizará su dni como clave de acceso']]);
        return redirect()->route('admin.alumnos.index')->with('mensajes', $this->mensajes);

    }

}
