<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CrearCarreraRequest;
use App\Http\Requests\EditarCarreraRequest;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Repositories\Admin\CarreraRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarrerasCrudController extends BaseController
{

    public $defaultFilters = [
        'filter_vigente' => 0,
    ];

    public $carreraRepo;

    public function __construct(CarreraRepository $carreraRepo) {
        parent::__construct();
        $this->carreraRepo = $carreraRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->setFilters($request);
        $this->data['carreras'] = $this->carreraRepo->index($request);
        return view('Admin.Carreras.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.Carreras.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearCarreraRequest $request)
    {
        $data = $request->validated();

        $data['vigente'] = 1;

        Carrera::create($data);
        return redirect()->route('admin.carreras.index');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,Carrera $carrera)
    {
        return view('Admin.Carreras.edit', ['carrera'=> Carrera::where('id',$carrera->id)->with('asignaturas')->first()]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditarCarreraRequest $request, Carrera $carrera)
    {
        $datos = $request->validated();

        if($request->has('resolucion_archivo')){
            $request->file('resolucion_archivo')->storeAs(str_replace(' ','_',$datos['nombre']).'.pdf');
            $datos['resolucion_archivo'] = str_replace(' ','_',$datos['nombre']).'.pdf';
        }


        $carrera->update($datos);

        if(!$request->has('vigente')){
            $carrera->vigente=true;
            $carrera->save();
        }
        if($request->has('redirect'))
            return redirect()->to($request->input('redirect'))->with('mensaje','Se edito la carrera');
        else
            return redirect()->back()->with('mensaje','Se edito la carrera');


        // return redirect()->back()->with('mensaje','Se edito la carrera');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carrera $carrera)
    {
        return redirect() -> route('admin.carreras.index') -> with('error', 'Las carreras no se pueden eliminar');
    }

}
