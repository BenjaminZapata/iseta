<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Examen;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Correlativa;
use App\Models\Cursada;
use App\Repositories\Admin\CursadaRepository;
use App\Repositories\AdminCursadaRepository;
use Illuminate\Http\Request;

class CursadasAdminController extends BaseController
{
    public $defaultFilters = [
        'filter_carrera_id' => 0,
        'filter_asignatura' => 0,
        'filter_alumno_id' => 0,
        'filter_condicion' => 0,
        'filter_aprobada' => 0
    ];

    function __construct()
    {
        parent::__construct();
        $this -> middleware('auth:admin');
    }

    public function index(Request $request, CursadaRepository $cursadaRepo)
    {
        $this->setFilters($request);
        $this->data['cursadas'] = $cursadaRepo->index($request);
        return view('Admin.Cursadas.index', $this->data);
    }

    function delete(Cursada $cursada){
        $cursada -> delete();
        return redirect() -> route('Admin.Cursadas.index');
    }

    function edit(Request $request, Cursada $cursada){
        //$cursada = Cursada::where('id_asignatura',$asignatura)->where('id_alumno',$alumno)->first();
        return view('Admin.Cursadas.edit',compact('cursada'));
    }

    function update(Request $request, Cursada $cursada){
        $data = $request->except('_token','_method');
        $mensajes = [];

        if( $request->input('condicion') == 0 ||
            $request->input('condicion') == 2 ||
            $request->input('condicion') == 3){


            if($cursada->aprobada == 1 && ($request->aprobada==2 || $request->aprobada==3)){
                $mensajes[] = "No puedes desaprobar una cursada libre, promocionada o aprobada por equivalencias";
            }

            $data['aprobada'] = 1;
        }

        $cursada -> update($data);
        $mensajes[] = 'Se ha editado correctamente';

        if($request->has('redirect'))
            return redirect()->to($request->input('redirect'))->with('mensaje',$mensajes);
        else
            return redirect()->back()->with('mensaje',$mensajes);


    }

    function create(){
        $alumnos = Alumno::orderBy('nombre','asc')->orderBy('apellido','asc')->get();
        $carreras = Carrera::vigentes();

        return view('Admin/Cursadas/create',[
            'alumnos' => $alumnos,
            'carreras' => $carreras
        ]);

    }

    function store(Request $request){

        $asignatura = Asignatura::where('id',$request->id_asignatura)->with('correlativas.asignatura')->first();
        $alumno = Alumno::find($request->id_alumno);


        // Ver que no este ya anotado o que ya la haya aprobado
        $yaAnotadoEnCursada = Cursada::where('id_alumno', $alumno->id)
            -> whereRaw('(aprobada=3 OR aprobada=1)')
            -> where('id_asignatura', $asignatura->id)
            -> first();

        // Si lo esta, no incluir
        if($yaAnotadoEnCursada) {
            return \redirect()->back()->with('error', 'El alumno ya registra una cursada de la asignatura del año '.$yaAnotadoEnCursada->anio_cursada)->withInput();
        }

        // Obtener datos de la asignatura con sus correlativas
        $correlativas = Correlativa::debeCursadasCorrelativos($asignatura,$alumno);

        if($correlativas){
            $mensajes=[];
            foreach($correlativas as $correlativa){
                $mensajes[] = 'Debe la cursada de '.$correlativa->nombre;
            }
            return \redirect()->back()->with(['error'=>$mensajes])->withInput();
        }

        if ($request->aprobada == 5) {
            Examen::create($request->all()); // Equivalencia
        }

        Cursada::create([
            'id_carrera' => $request->id_carrera,
            'id_asignatura' => $request->id_asignatura,
            'id_alumno' => $request->id_alumno,
            'anio_cursada' => $request->anio_cursada,
            'condicion' => $request->condicion,
            'aprobada' => $request->aprobada
        ]);

        return redirect()->back()->with('mensaje','Se creo la cursada');
    }
}
