<?php

namespace App\Models;

use App\Services\TextFormatService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asignatura extends Model
{
    protected $table = "asignaturas";
    use HasFactory;

    public $timestamps = false;

    protected $fillable =  [
        'nombre',
        'tipo_modulo',
        'carga_horaria',
        'anio',
        'observaciones'
    ];

    public function cursadas(): HasMany{
        return $this -> hasMany(related: Cursada::class, foreignKey: 'id_asignatura')
            -> where(column: 'anio_cursada', operator: Configuracion::get(key: 'anio_ciclo_actual'));
    }

    public function profesor(): BelongsToMany{
        return $this -> belongsToMany(Profesor::class, 'carrera_asignatura_profesor', 'id_asignatura', 'id_profesor')
            -> withPivot('id_carrera')
            -> withTimestamps();
    }

    public function carrera(): BelongsToMany{
        return $this -> belongsToMany(Carrera::class, 'carrera_asignatura_profesor', 'id_asignatura', 'id_carrera')
            -> withPivot('id_profesor')
            -> withTimestamps();
    }
    public function correlativas(): HasMany{
        return $this -> hasMany(Correlativa::class, 'id_asignatura');
    }

    public function mesas(): HasMany{
        return $this -> hasMany(related: Mesa::class, foreignKey: 'id_asignatura')->whereRaw(sql: 'fecha >= NOW()');
    }

    public function getAnioAttribute($value): float|int{
        return $value + 1;
    }

    public function estaCursando($alumno) {
        return Cursada::where(column: 'id_alumno', operator: $alumno->id)
            -> where(column: 'id_asignatura', operator: $this->id)
            -> where(column: 'aprobada', operator: 3)
            -> where(column: 'condicion', operator: 1)
            -> first();
    }

    public function aproboExamen($alumno): ?Examen{
        $examen = Examen::where(column: 'id_alumno',operator: $alumno->id)
            -> where(column: 'id_asignatura', operator: $this->id)
            -> where(column: 'nota',operator: '>=',value: 4)
            -> first();

        if ($examen) {
            return $examen;
        }
        else {
            return null;
        }
     }

    public function aproboCursada($alumno){
        $cursada = Cursada::where(column: 'id_alumno', operator: $alumno->id)
        -> where(column: 'id_asignatura',operator: $this->id)
        -> where(column: function($subQuery): void{
            $subQuery -> where('aprobada', 1);
        })
        ->first();

        if($cursada) {
            return $cursada;
        }
        else {
            return null;
        }
     }

    public function cursantes(){
        $config=Configuracion::todas();
        return Alumno::select('cursadas.anio_cursada', 'cursadas.condicion', 'cursadas.id as cursada_id', 'alumnos.id', 'alumnos.nombre', 'alumnos.apellido', 'alumnos.dni', 'asignaturas.id')
            -> join('cursadas','cursadas.id_alumno','alumnos.id')
            -> join('asignaturas','cursadas.id_asignatura','asignaturas.id')
            -> where('asignaturas.id', $this->id)
            // -> where('cursadas.aprobada', 3)
            -> where('anio_cursada', $config['anio_ciclo_actual'])
            -> get();
    }

    public function tieneLaCorrelativa($id){
        return Correlativa::where('id_asignatura', $this->id)
        ->where('asignatura_correlativa', $id)
        ->first();
    }

    public function anioStr(): string{
        $strings = ['Primer año','Segundo año','Tercer año', 'Cuarto año', 'Quinto año', 'Sexto año'];
        return $strings[$this->anio-1];
    }

    public function setNombreAttribute($value): void{
        $this->attributes['nombre'] = TextFormatService::ucwords($value);
    }

    public function setAnioAttribute($value): void{
        $this->attributes['anio'] = $value-1;
    }

    public function setObservacionesAttribute($value)
    {
        $this->attributes['observaciones'] = TextFormatService::ucfirst($value);
    }


}

