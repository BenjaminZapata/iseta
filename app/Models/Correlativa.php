<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;

class Correlativa extends Pivot
{
    use HasFactory;

    protected $table = 'correlatividades';

    protected $fillable = [
        'id_asignatura',
        'id_carrera',
        'id_asignatura_correlativa',
        'tipo_correlativa',
    ];

    public $timestamps = false;

    public function asignatura()
    {
        return $this->BelongsTo(Asignatura::class, 'id_asignatura');
    }

    public static function debeExamenesCorrelativos($asignatura, $id_carrera, $alumno)
    {
        if (! $alumno) {
            $alumno = Auth::user();
        }

        $asignatura->with('correlativas')
            ->first();

        $sinAprobar = [];

        foreach ($asignatura->correlativas->wherePivot('id_carrera', $id_carrera)->get() as $correlativa) {
            $asigCorr = $correlativa;
            if (! $asigCorr) {
                return false;
            }
            if ($asigCorr->aproboExamen($alumno)) {
                continue;
            } else {
                $sinAprobar[] = $asigCorr;
            }
        }

        if (count($sinAprobar) > 0) {
            return $sinAprobar;
        } else {
            return false;
        }
    }

    public static function debeCursadasCorrelativas($asignatura, $alumno): array|bool
    {
        if (! $alumno) {
            $alumno = Auth::user();
        }

        $sinAprobar = [];
        foreach ($asignatura->correlativas as $correlativa) {
            if ($correlativa === null) {
                return false;
            }
            if (! $correlativa->aproboCursada($alumno)) {
                $sinAprobar[] = $correlativa->toArray();
            }
        }

        if (count($sinAprobar) > 0) {
            return $sinAprobar;
        }

        return false;
    }
}
