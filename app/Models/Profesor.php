<?php

namespace App\Models;

use App\Services\TextFormatService;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Profesor extends Authenticatable
{
    use HasFactory, ModelTrait;

    protected $table = "profesores";
    public $timestamps = false;

    protected $fillable = [
        'dni',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'ciudad',
        'calle',
        'casa_numero',
        'dpto',
        'piso',
        'estado_civil',
        'email',
        'formacion_academica',
        'titulo',
        'anio_ingreso',
        'observaciones',
        'telefono1',
        'telefono2',
        'telefono3',
        'codigo_postal',
        'password',
        'lugar_nacimiento',

    ];

    protected $casts = [
        'fecha_nacimiento' => 'datetime',
    ];

  public function asignaturas(): BelongsToMany
{
    return $this->belongsToMany(
        Asignatura::class,                 // Modelo relacionado
        'carrera_asignatura_profesor',     // Nombre de la tabla pivote
        'id_profesor',                     // FK hacia este modelo en la pivote
        'id_asignatura'                    // FK hacia el modelo relacionado
    )
    ->using(CarreraAsignaturaProfesor::class) // Modelo de pivote
    ->withPivot('id_carrera')                   // Campos adicionales en la pivote
    ->withTimestamps();
}
    public function profesor_mesa(): HasMany
    {
        return $this->hasMany(Mesa::class, 'prof_presidente', 'id');
    }

    public function profesor_mesa_vocal(): HasMany
    {
        return $this->hasMany(Mesa::class, 'prof_vocal_1', 'id');
    }

    public function profesor_mesa_vocal2(): HasMany
    {
        return $this->hasMany(Mesa::class, 'prof_vocal_2', 'id');
    }

    public function firstItemsForSelect()
    {
        return ['0' => 'Vacio/A confirmar'];
    }

    function others()
    {
        return Profesor::where('id', 3)->get();
    }

    function elementsForDropdown($filter)
    {
        if ($filter == 'order') {
            return Profesor::select()->orderBy('apellido')->orderBy('nombre')->get();
        }
    }

    function textForSelect()
    {
        return $this->apellidoNombre();
    }

    static function existeSinPassword($data)
    {
        return Profesor::where('email', $data['email'])
            ->where('password', '0')
            ->where('dni', $data['dni'])
            ->first();
    }
    public function verificar()
    {
        $this->verificado = 1;
        $this->save();
    }

    public function nombreApellido()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    public function apellidoNombre()
    {
        return $this->apellido . ' ' . $this->nombre;
    }

    public function dniPuntos()
    {
        return number_format($this->dni, 0, ',', '.');
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = TextFormatService::ucwords($value);
    }

    public function setApellidoAttribute($value)
    {
        $this->attributes['apellido'] = TextFormatService::ucwords($value);
    }

    public function setCiudadAttribute($value)
    {
        $this->attributes['ciudad'] = TextFormatService::ucfirst($value);
    }

    public function setObservacionesAttribute($value)
    {
        $this->attributes['observaciones'] = TextFormatService::ucfirst($value);
    }

    public function setCalleAttribute($value)
    {
        $this->attributes['calle'] = TextFormatService::ucfirst($value);
    }
}
