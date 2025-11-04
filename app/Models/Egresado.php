<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Egresado extends Model
{
    use HasFactory;

    protected $table = 'egresadoinscripto';

    public $timestamps = false;

    protected $fillable = [
        'id_alumno',
        'id_carrera',
        'anio_inscripcion',
        'indice_libro_matriz',
        'anio_finalizacion',
        'estado'];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id');
    }

    public static function estaInscripto($carrera, $alumno = null)
    {
        if (! $alumno) {
            $alumno = Auth::user();
        }

        $existe = Egresado::where('id_alumno', $alumno->id)
            ->where('id_carrera', $carrera)
            ->exists();

        return $existe;
    }

    public function getEstadoTextoAttribute()
    {
        $estado = ['Cursando', 'Egresado', 'Desertor'];

        return $estado[$this->attributes['estado']] ?? 'Otro';
    }
}
