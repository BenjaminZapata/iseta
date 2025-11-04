<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $table = "examenes";

    protected $fillable = [
        'id_mesa',
        'id_asignatura',
        'id_carrera',
        'id_alumno',
        'libro',
        'acta',
        'nota',
        'fecha',
        'aprobado',     // 0 = Desaprobado, 1 = Aprobado
        'tipo_final',
        'estado',
        'asistencia'    // 0 = Ausente, 1 = Presente
    ];

    public $timestamps = false;

    /* ===========================
       Relaciones
    ============================*/

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'id_mesa');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno');
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura');
    }

    /* ===========================
       Atributos y Métodos
    ============================*/

    public function getFecha()
    {
        if ($this->fecha) {
            return $this->fecha;
        }

        return optional($this->mesa)->fecha;
    }

    public function tipoFinalTexto()
    {
        return match ($this->tipo_final) {
            1 => "Escrito",
            2 => "Oral",
            3 => "Promocionado",
            4 => "Equivalencia",
            default => "Sin especificar"
        };
    }

    /**
     * Devuelve una descripción legible de la nota
     */
    public function notaTexto()
    {
        // Si el alumno estuvo ausente
        if ($this->asistencia === 0) {
            return 'Ausente';
        }

        // Si no tiene nota cargada
        if (is_null($this->nota) || $this->nota === 0) {
            return 'Sin calificar';
        }

        // Mostrar nota y estado
        $estado = $this->aprobado === 1 ? 'Aprobado' : 'Desaprobado';
        return "{$this->nota} ({$estado})";
    }


    public function asistenciaTexto()
    {
        return match ($this->asistencia) {
            0 => "Ausente",
            1 => "Presente",
            default => "Desconocido"
        };
    }

    public function aprobadoTexto()
    {
        return match ($this->aprobado) {
            0 => "Desaprobado",
            1 => "Aprobado",
            default => "Desconocido"
        };
    }
}
