<?php

namespace App\Repositories;

use App\Models\Carrera;
use App\Models\CarreraDefault;
use App\Models\Configuracion;
use App\Models\Cursada;
use App\Models\Examen;
use Illuminate\Support\Facades\Auth;

class AlumnoDataRepository
{
    public $config;

    public function __construct() {
        $this->config = Configuracion::todas();
    }


    function examenes($filtro,$campo,$orden){
        $defaultCarreraId = Carrera::getDefault()?->id;
        return Examen::join('asignaturas', 'asignaturas.id','examenes.id_asignatura')
        ->join('carrera_asignatura_profesor', 'carrera_asignatura_profesor.id_asignatura', '=', 'asignaturas.id')
        ->where('carrera_asignatura_profesor.id_carrera', $defaultCarreraId)
        -> where('examenes.id_alumno', Auth::id())
        -> when($filtro, fn($query) => $query->where('asignaturas.nombre','LIKE',"%$filtro%"))
        -> when($campo=='aprobadas',fn($query) => $query->where('examenes.nota','>=',4))
        -> when($campo=='desaprobadas',fn($query) => $query->where('examenes.nota','<',4))
        -> orderBy('asignaturas.anio')
        -> orderBy('asignaturas.id')
        -> orderBy('examenes.fecha', 'desc')
        -> orderBy('examenes.nota','asc')
        -> get();
    }

    function cursadas($filtro, $campo, $orden){

        $query = Cursada::with('asignatura')->select('cursadas.id_asignatura','cursadas.anio_cursada','cursadas.id','cursadas.aprobada','cursadas.condicion','asignaturas.nombre','asignaturas.anio')
            ->where('id_alumno', Auth::id())
            -> join('asignaturas','asignaturas.id','cursadas.id_asignatura')
            ->join('carrera_asignatura_profesor as cap', 'cap.id_asignatura', 'asignaturas.id')
            ->where('cap.id_carrera', Carrera::getDefault(Auth::id())->id)
            // si tiene un filtro en el campo de texto
            -> when($filtro, fn($query,$filtro) => $query -> where('asignaturas.nombre','LIKE','%'.$filtro.'%'))

            // Si se filtra por aprobadas
            -> when($campo == "aprobadas", function($query){
                $query->where(function($sub){
                    $sub -> where('cursadas.aprobada', 1)
                        ->orWhereIn('cursadas.condicion',[0,2,3]);
                });
            })

            // Si se filtra por desaprobadas
            ->when($campo == "desaprobadas", function($query){
                $query-> where('cursadas.aprobada', 2)
                ->whereNotIn('cursadas.condicion',[0,2,3]);
            })

            // Ordenamiento
            -> when($orden == 'anio', fn($query)=>$query->orderBy('asignaturas.anio'))
            -> when($orden == 'anio_cursada', fn($query)=>$query->orderBy('cursadas.anio_cursada'))
            -> when($orden == 'anio_desc', fn($query)=>$query->orderBy('asignaturas.anio','desc'))
            -> when($orden == 'anio_cursada_desc', fn($query)=>$query->orderBy('cursadas.anio_cursada','desc'));

        $query -> orderBy('asignaturas.anio')
        -> orderBy('asignaturas.id')
        -> orderBy('cursadas.anio_cursada','desc');

        return $query->get();
    }

    function setCarreraDefault($alumno,$carrera){
        $data = [
            'id_alumno' => $alumno,
            'id_carrera' => $carrera
        ];

        CarreraDefault::updateOrInsert(['id_alumno' => $alumno], $data);
    }
}
