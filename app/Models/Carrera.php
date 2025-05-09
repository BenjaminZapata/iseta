<?php

namespace App\Models;

use App\Services\TextFormatService;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Auth;

class Carrera extends Model
{
    use HasFactory, ModelTrait;
    protected $table = "carreras";
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'resolucion',
        'anio_apertura',
        'anio_fin',
        'observaciones',
        'vigente',
        'resolucion_archivo'
    ];

    /**
     * Asignaturas que pertenecen a la carrera
     * @return BelongsToMany
     */
    public function asignaturas(): BelongsToMany{
        return $this -> belongsToMany(Asignatura::class, 'carrera_asignatura_profesor', 'id_carrera', 'id_asignatura');
    }

    public function primeraAsignatura(){
        return Asignatura::where('id_carrera', $this->id)->orderBy('anio')->first();
    }

    public function textForSelect(){
        return $this->nombre;
    }

    public static function getAsignaturas($id_carrera){
        $asignaturas = CarreraAsignatura::select("id_asignatura")
            -> where('id_carrera',$id_carrera)
            -> get();
        if ($asignaturas->isEmpty()) return null;
        return $asignaturas;
    }

    public static function getDefault($alumno_id=null){

        $alumno = $alumno_id ? Alumno::find($alumno_id) : Auth::user();
        $carrera = CarreraDefault::select('id_carrera')
            -> where('id_alumno',$alumno->id)
            -> first();

            if($carrera) return Carrera::find($carrera->id_carrera);

            $carrera = Egresado::select('carreras.id', 'carreras.nombre')
                -> join('carreras','egresadoinscripto.id_carrera','carreras.id')
                -> where('egresadoinscripto.id_alumno',$alumno->id)
                -> first();

            if(!$carrera) return null;
            return $carrera;
    }

    function estaInscripto($alumno=null){

        if  (!$alumno) {
            $alumno=Auth::user();
        }

        return Egresado::where('id_alumno',$alumno->id)
            ->where('id_carrera', $this->id)
            ->exists();
    }

    static function vigentes(){
        return Carrera::where('vigente',1)->get();
    }

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = TextFormatService::ucwords($value);
    }

    public function setObservacionesAttribute($value)
    {
        $this->attributes['observaciones'] = TextFormatService::ucfirst($value);
    }


}
