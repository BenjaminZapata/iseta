<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CrearAsignaturaRequest;
use App\Http\Requests\EditarAsignaturaRequest;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class AsignaturasCrudController extends Controller
{

    function __construct()
    {
        $this -> middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $asignaturas = [];
         $filtro = "";
         $porPagina = Configuracion::get('filas_por_tabla',true);


        if($request->has('filtro')){
            $filtro = $request->filtro;

            if(strpos($filtro, ':')){
                $arr = explode(':',$filtro);
                $campo = $arr[0];
                $keyword = $arr[1];
                $asignaturas = Asignatura::where($campo,'LIKE','%'.$keyword.'%') -> paginate($porPagina);
            }else{

                $asignaturas = Asignatura::where('nombre','LIKE','%'.$filtro.'%')
                    -> paginate($porPagina);
            }
        }else{
            $asignaturas = Asignatura::select('*')->with('carrera')->paginate($porPagina);
        }
        return view('Admin.Asignaturas.index',['asignaturas'=>$asignaturas, 'filtro'=>$filtro]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $carreras = Carrera::orderBy('nombre')->get();
        return view('Admin.Asignaturas.create',[
            'carreras'=>$carreras,
            'id_carrera'=>$request->id_carrera? $request->id_carrera:null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearAsignaturaRequest $request)
    {
        $data = $request->validated();

        if(!Carrera::where('id', $data['id_carrera'])->exists()){
            return redirect()->back()->with('error','La carrera seleccionada no existe');
        }


        Asignatura::create($data);

        return redirect()->back()->with('mensaje','Se creo la asignatura');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $asignatura)
    {
        Configuracion::todas();

        $asignatura = Asignatura::with('cursadas.alumno')->find($asignatura);

        $alumnos = $asignatura->cursantes();

        //$correlativas = Asignatura::where('id_carrera', $asignatura->carrera->id)
         //   ->where('anio', '>=', $asignatura->anio);


        return view('Admin.Asignaturas.edit', [
            'asignatura' => $asignatura,
            // 'alumnos' => $alumnos
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditarAsignaturaRequest $request, Asignatura $asignatura)
    {
        $data = $request->validated();
        $asignatura->update($data);

        if($request->has('redirect'))
            return redirect()->to($request->input('redirect'))->with('mensaje','Se edito la asignatura');
        else
            return redirect()->back()->with('mensaje','Se edito la asignatura');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asignatura $asignatura)
    {
        $asignatura->delete();
        return redirect() -> route('admin.carreras.edit',['carrera' => $asignatura->id_carrera]) -> with('mensaje', 'Se ha eliminado la asignatura');
    }
}
