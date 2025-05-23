<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\crearAlumnoRequest;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Egresado;
use App\Repositories\Admin\InscripcionRepository;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnSelf;

class EgresadosAdminController extends BaseController
{

    public $defaultFilters = [
        'filter_carrera_id' => 0,
        'filter_alumno_id' => 0,
        'filter_vigente' => 0,
        'filter_finalizada' => 0,
        'filter_ciudad' => 0
    ];

    function __construct()
    {
        parent::__construct();
        $this -> middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, InscripcionRepository $inscriptosRepo)
    {
        $this->setFilters($request);
        $this->data['inscripciones'] = $inscriptosRepo->index($request);

        return view('Admin.Inscriptos.index',$this->data);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.Inscriptos.create',[
            'alumnos'=>Alumno::orderBy('apellido')->orderBy('nombre')->get(),
            'carreras'=>Carrera::where('vigente','1')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_alumno' => ['required'],
            'id_carrera' => ['required'],
            'anio_inscripcion' => ['required'],
            'indice_libro_matriz' => ['nullable'],
            'anio_finalizacion' => ['nullable']
        ]);

        Egresado::create($data);

        if($request->has('redirect'))
            return redirect()->to($request->input('redirect'))->with('mensaje','Se creo la inscripción');
        else
            return redirect()->route('admin.Inscriptos.index')->with('mensaje','Se creo la inscripción');

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $registro)
    {
        $registro = Egresado::find($registro);
        if(!$registro) return \redirect()->route('admin.Inscriptos.index')->with('aviso','La inscripcion no existe');


        return view('Admin.Inscriptos.edit', [
            'registro' => $registro,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $registro)
    {
        $registro = Egresado::find($registro);
        $registro->update($request->all());
        return redirect()->back()->with('mensaje','Se actualizo correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($alumno)
    {
        Egresado::find($alumno)->delete();
        return redirect() -> route('admin.Inscriptos.index') -> with(['mensaje' => [
            'Se ha eliminado la inscripcion',
            'Recuerda que puedes volver a crearla en el apartado "crear inscripcion"'
        ]]);
    }
}
