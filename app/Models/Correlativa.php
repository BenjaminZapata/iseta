<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public static function debeExamenesCorrelativos($asignatura, $carrera, $alumno)
    {
        if (! $alumno) {
            $alumno = Auth::user();
        }
        $asignatura = Asignatura::with('correlativas.asignatura')
            ->where('id', $asignatura->id)
            ->first();

        $sinAprobar = [];

        foreach ($asignatura->correlativas as $correlativa) {
            $asigCorr = $correlativa->asignatura;
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

    public static function debeCursadasCorrelativos($asignatura, $carrera, $alumno)
    {
        if (! $alumno) {
            $alumno = Auth::user();
        }
        $asignatura = Asignatura::with('correlativas')
            ->where('id', $asignatura->id)
            ->first();

        $sinAprobar = [];

        foreach ($asignatura->correlativas()->wherePivot('id_carrera', $carrera->id)->get() as $correlativa) {
            $asigCorr = $correlativa->asignatura;
            if (is_null($asigCorr)) {
                return false;
            }
            Log::debug($asigCorr->aproboCursada($alumno));
            if (! $asigCorr->aproboCursada($alumno)) {
                $sinAprobar[] = $asigCorr;
            }
        }

        if (count($sinAprobar) > 0) {
            return $sinAprobar;
        }

        return false;
    }
}
