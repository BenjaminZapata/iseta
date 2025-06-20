<?php


namespace App\Repositories\Admin;


use App\Models\Alumno;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Correlativa;
use App\Models\Cursada;
use App\Models\Egresado;
use App\Models\Examen;
use App\Models\Mesa;
use Illuminate\Support\Facades\Auth;


class InscripcionRepository
{
    public $config;
    public $availableFiels = ['anio_inscripcion','anio_finalizacion'];


    public function __construct() {
        $this->config = Configuracion::todas();
    }


    function index($request){
        $idsQuery = Egresado::select('egresadoinscripto.id')
            ->leftJoin('alumnos', 'alumnos.id', 'egresadoinscripto.id_alumno')
            ->leftJoin('carreras', 'carreras.id', 'egresadoinscripto.id_carrera');


        if($request->has('filter_carrera_id') && $request->input('filter_carrera_id') != 0){
            $idsQuery->where('egresadoinscripto.id_carrera', $request->input('filter_carrera_id'));
        }


        if($request->has('filter_alumno_id') && $request->input('filter_alumno_id') != 0)
            $idsQuery->where('egresadoinscripto.id_alumno', $request->input('filter_alumno_id'));


      if ($request->has('filter_estado') && $request->input('filter_estado') != 'Cualquiera') {
    $idsQuery->where('egresadoinscripto.estado', $request->input('filter_estado'));
    }
    if ($request->has('filter_ciudad') && $request->input('filter_ciudad') != 0) {
    $idsQuery->where('alumnos.ciudad', $request->input('filter_ciudad'));
    }


    if ($request->filled('anio_inscripcion')) {
    $idsQuery->where('egresadoinscripto.anio_inscripcion', $request->input('anio_inscripcion'));
    }


    if ($request->filled('anio_finalizacion')) {
    $idsQuery->where('egresadoinscripto.anio_finalizacion', $request->input('anio_finalizacion'));
    }






    if($request->has('filter_search_box') && in_array($request->input('filter_field'),$this->availableFiels)){
        $idsQuery->where($request->input('filter_field'), 'LIKE', '%'.$request->input('filter_search_box').'%');
    }


        $ids = $idsQuery->distinct()->get()->pluck('id');


        $cursadas = Egresado::with('alumno','carrera')
        ->whereIn('egresadoinscripto.id', $ids)
          ->orderBy('anio_inscripcion', 'desc')    
          ->orderByRaw("
        CASE estado
            WHEN 'Cursando' THEN 0
            WHEN 'Egresado' THEN 1
            WHEN 'Desertor' THEN 2
            ELSE 3
        END
    ")


        ->paginate($this->config['filas_por_tabla']);




        return $cursadas;


    }


    public function crearInscripcion($mesa, $alumno){
        Examen::create([
            'id_mesa' => $mesa->id,
            'id_alumno'  => $alumno->id,
            'nota'=>'0.00',
            'id_asignatura' => $mesa->id_asignatura,
            'fecha' => \now()
        ]);
    }


    public function borrar($examen){
        $examen->delete();
    }




    public function inscribibles($alumno){
        $asignaturas = Asignatura::with('mesas.anotado')
        -> where('id_carrera', Carrera::getDefault()->id)
        -> get();


    $examenesInscriptos = Examen::select('id_mesa')
        -> where('id_alumno', $alumno->id)
        -> get() -> pluck('id_mesa') -> toArray();


    $posibles = [];
    $reg = [];


    foreach ($asignaturas as $key=>$asignatura) {
        $reg = [
            'asignatura' => null,
            'correlativas' => null,
            'yaAnotado' => null,
        ];


        if(count($asignatura->mesas) == 0) continue;
        if($asignatura->aproboExamen(Auth::user())) continue;
        if(!$asignatura->aproboCursada(Auth::user())) continue;


        $reg['asignatura'] = $asignatura;


        foreach($asignatura->mesas as $mesa){
            if(in_array($mesa->id, $examenesInscriptos)) {
                $reg['yaAnotado'] = $mesa; break;
            }
        }


        $correlativas = Correlativa::debeExamenesCorrelativos($asignatura);


        if($correlativas){
            $reg['correlativas'] = $correlativas;
        }
        $posibles[] = $reg;
    }


    return $posibles;
    }


}


