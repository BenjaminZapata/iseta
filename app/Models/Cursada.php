<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Awobaz\Compoships\Compoships;

class Cursada extends Model
{
    protected $table = 'cursadas';

    use HasFactory;

    protected $fillable = [
        'anio_cursada',
        'aprobada',
        'id_alumno',
        'id_asignatura',
        'id_carrera',
        'condicion',
        'primer_cuatrimestre_nota',
        'segundo_cuatrimestre_nota',
        'observaciones',
    ];

    public function alumno()
    {
        return $this->hasOne(Alumno::class, 'id', 'id_alumno');
    }

    public function cursadas()
    {
        return $this->hasMany(Cursada::class, ['id_carrera', 'id_asignatura', 'anio_cursada'], ['id_carrera', 'id_asignatura', 'anio_cursada']);
    }


    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id');
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura', 'id');
    }

    /** INFO: no eliminar campos "Promocion", "Desertor" y "Equivalencia"
     * son utilizados para mantener funcionalidades antiguas */
    public function condicionString(): string
    {
        return match ($this->condicion) {
            0 => 'Libre',
            1 => 'Regular',
            2 => 'Promocion',
            3 => 'Equivalencia',
            4 => 'Desertor',
            5 => 'Itinerante',
            6 => 'Oyente',
            default => 'Otro',
        };
    }

    public function aprobado(): string
    {
        return match ($this->aprobada) {
            1 => 'Aprobada',
            2 => 'Reprobada',
            3 => 'Cursando',
            4 => 'Promocion',
            5 => 'Equivalencia',
            default => 'Otro',
        };
    }
}