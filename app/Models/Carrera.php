<?php

namespace App\Models;

use App\Services\TextFormatService;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Carrera extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'carreras';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'resolucion',
        'anio_apertura',
        'anio_fin',
        'observaciones',
        'vigente',
        'resolucion_archivo',
    ];

    /**
     * Asignaturas que pertenecen a la carrera
     */
    public function asignaturas(): BelongsToMany
    {
        return $this->belongsToMany(Asignatura::class, 'carrera_asignatura_profesor', 'id_carrera', 'id_asignatura')
            ->withPivot('id_profesor', 'anio', 'tipo_modulo', 'carga_horaria')
            ->using(CarreraAsignaturaProfesor::class)
            ->withTimestamps();
    }

    public function cursadas(): HasMany
    {
        return $this->hasMany(Cursada::class, 'id_carrera', 'id');
    }

    public function profesores(): BelongsToMany
    {
        return $this->BelongsToMany(Profesor::class, 'carrera_asignatura_profesor', 'id_carrera', 'id_profesor')
            ->withPivot('id_asignatura')
            ->withTimestamps();
    }

    public function resolucionArchivo()
    {
        return Carrera::where('id', $this->id)->first()->resolucion_archivo;
    }

    public function primeraAsignatura()
    {
        return Asignatura::Has('carrera', $this->id)->orderBy('anio')->first();
    }

    public function textForSelect()
    {
        return $this->nombre;
    }

    public static function getAsignaturas($id_carrera)
    {
        $asignaturas = Asignatura::select('id_asignatura')
            ->where('id_carrera', $id_carrera)
            ->get();
        if ($asignaturas->isEmpty()) {
            return null;
        }

        return $asignaturas;
    }

    public static function getDefault($alumno)
    {

        $carrera = $alumno->carreraDefault()->first();

        if ($carrera) {
            return $carrera->carrera()->get();
        }

        $carrera = $alumno->egresado()->first()->carrera()->first();
        /* Egresado::select('id_carrera')
            ->where('id_alumno', $alumno->id)
            ->first();
        */
        if (! $carrera) {
            return null;
        }

        return $carrera;
    }

    public function estaInscripto($alumno = null)
    {

        if (! $alumno) {
            $alumno = Auth::user();
        }

        return Egresado::where('id_alumno', $alumno->id)
            ->where('id_carrera', $this->id)
            ->exists();
    }

    public static function vigentes()
    {
        return Carrera::where('vigente', 1)->get();
    }

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = TextFormatService::ucwords($value);
    }

    public function setObservacionesAttribute($value)
    {
        $this->attributes['observaciones'] = TextFormatService::ucfirst($value);
    }

    public function inscriptos()
    {
        return $this->hasMany(Egresado::class, 'id_carrera', 'id');
    }

    public function mesas()
    {
        return $this->hasMany(Mesa::class, 'id_carrera', 'id');
    }

    // En CarreraM
    public function listadoNombres()
    {
        return Carrera::pluck('nombre', 'nombre')->toArray();
    }

    public function listadoResoluciones()
    {
        return Carrera::pluck('resolucion', 'resolucion')->toArray();
    }

    public function numerosResolucion()
    {
        return Carrera::selectRaw("SUBSTRING_INDEX(resolucion, '/', 1) as numero")
            ->distinct()->pluck('numero', 'numero')->toArray();
    }

    public function aniosResolucion()
    {
        return Carrera::selectRaw("SUBSTRING_INDEX(resolucion, '/', -1) as anio")
            ->distinct()->pluck('anio', 'anio')->toArray();
    }
}
