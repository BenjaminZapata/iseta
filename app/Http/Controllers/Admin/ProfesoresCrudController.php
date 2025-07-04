<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
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

    function __construct(ProfesorRepository $profeRepo)
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

        return view('Admin.Profesores.index',$this->data);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.profesores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(crearProfesorRequest $request)
    {
        $data = $request->validated();

        Profesor::create($data);
        return redirect()->route('admin.profesores.index')->with('mensaje','Se creo el profesor');
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
            'mesas' => $mesas
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditarProfesorRequest $request, Profesor $profesor)
    {
        $profesor->update($request->all());
        return redirect()->route('admin.profesores.index')->with('mensaje','Se edito el profesor');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profesor $profesor)
    {
        $profesor->delete();
        return redirect() -> route('admin.profesores.index') -> with('mensaje', 'Se ha eliminado el profesor');
    }



}
