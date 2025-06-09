<?php

namespace App\Models;

use App\Services\TextFormatService;
use App\Traits\ModelTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class Alumno extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, ModelTrait;

    protected $table = 'alumnos';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dni',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'ciudad',
        'calle',
        'casa_numero' ,
        'dpto' ,
        'piso' ,
        'estado_civil' ,
        'email',
        'nombre_institucion_secundario',
        'titulo_anterior' ,
        'becas',
        'observaciones',
        'telefono1',
        'telefono2' ,
        'telefono3',
        'codigo_postal',    
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_nacimiento' => 'datetime',
    ];

    static function existeSinPassword($data){
        return Alumno::where('email', $data['email'])
            -> where('password','0')
            -> where('dni',$data['dni'])
            -> first();
    }

    public function verificar(){
        $this->verificado = 1;
        $this->save();
    }

    public function estadoCivilStr(){

        $estados_civiles = ['soltero/a','casado/a','divorciado/a','viudo/a','conyuge','otro'];

        if(isset($estados_civiles[$this->estado_civil])){
            return $estados_civiles[$this->estado_civil];
        }else{
            return 'Otro';
        }

    }

    public function cursadas(){
        return $this -> hasMany(Cursada::class,'id_alumno');
    }

    public function carreras(){
        return Egresado::with('carrera')->where('id_alumno',$this->id)->get();
    }

    public function carrerasIncriptas(){
        $alumno = $this->id;

        return Carrera::select('carreras.id as carrera_id','carreras.nombre as carrera_nombre')
            ->leftJoin('egresadoinscripto', 'egresadoinscripto.id_carrera','carreras.id')
            ->where('egresadoinscripto.id_alumno',$alumno)
            ->get();

    }

    public function examenes(){
        return $this -> hasMany(Examen::class, 'id_alumno');
    }

    function textForSelect(){
        return $this->apellidoNombre();
    }

    function elementsForDropdown($filter){
        if($filter=='orderByApellidoNombre'){
            return Alumno::select()->orderBy('apellido')->orderBy('nombre')->get();
        }
    }

    public function nombreApellido(){
        return $this->nombre.' '.$this->apellido;
    }

    public function apellidoNombre(){
        return $this->apellido.' '.$this->nombre;
    }

    public function dniPuntos(){
        return number_format($this->dni, 0, ',', '.');
    }

    public function primerNombre(){
        return explode(' ',$this->nombre)[0];
    }

    public function iniciales(){
        return "{$this->nombre[0]}.{$this->apellido[0]}.";
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

    function ciudades(){
        $result = Alumno::select('ciudad')->distinct('ciudad')->get()->pluck('ciudad');
        $ciudades = ['Cualquiera'];
        foreach($result as $ciudad){
            if(!in_array(trim($ciudad),$ciudades)){
                $ciudades[trim($ciudad)] = trim($ciudad);
            }
        }
        return $ciudades;
    }

public static function getInstitucionesSecundariasDisponibles()
{
    $result = self::select('nombre_institucion_secundario')
                ->distinct()
                ->pluck('nombre_institucion_secundario')
                ->filter(function ($value) {
                    return !is_null($value) && trim($value) !== '';
                });

    $instituciones = ['Cualquiera'];

    // Combina y elimina duplicados, asegurando que el array sea indexado correctamente
    return array_values(array_unique(array_merge($instituciones, $result->toArray())));
}
}
