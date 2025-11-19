<?php

namespace App\Services\Admin;

use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Correlativa;
use App\Models\Examen;
use App\Models\Mesa;
use App\Services\DiasHabiles;
use Carbon\Carbon;

class MesasCheckerService
{
    public $config;

    public function __construct()
    {
        $this->config = Configuracion::todas();
    }



    public function esDiaHabil($fecha)
    {
        // verificar que no sea sabado ni domingo
        if (DiasHabiles::esFinDeSemana($fecha)) {
            return ['success' => false, 'mensaje' => 'No puedes crear una mesa un fin de semana'];
        }

        // verificar que no sea feriado, o similar

        if (!DiasHabiles::esDiaHabil($fecha)) {
            return ['success' => false, 'mensaje' => 'No puedes crear una mesa un dia sin clase'];
        }

        return ['success' => true, 'mensaje' => 0];
    }

    function llamadoYaExiste($data)
    {
        // la fecha de la nueva mesa a crear.
        $fecha = Carbon::parse($data['fecha']);

        $fechaInicio = $fecha->copy()->subDays($this->config['diferencia_llamados']); // Restar 30 días
        $fechaFin = $fecha->copy()->addDays((int) ($this->config['diferencia_llamados'])); // Sumar 30 días

        // buscar mesa entre $fecha-30 dias y $fecha+30 dias, es decir un periodo de 30 dias desde ambos lados
        $registro = Mesa::with('asignatura')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->where('llamado', $data['llamado'])                // que sea el mismo llamado
            ->where('id_asignatura', $data['id_asignatura'])    // de la misma asignatura
            ->first();

        // si se encontro avisa que ya existe
        if ($registro) {
            $fechaMesa = Carbon::parse($registro->fecha);
            return ['success' => true, 'mensaje' => 'Ya hay un llamado ' . $data['llamado'] . ' para el dia ' . $fechaMesa->format('d/m')];
        }
        return ['success' => false, 'mensaje' => 0];
    }

    public function profesorDisponible($profesorId, $fecha)
    {
        return !Mesa::where('fecha', $fecha)
            ->where(function($q) use ($profesorId) {
                $q->where('prof_presidente', $profesorId)
                ->orWhere('prof_vocal_1', $profesorId)
                ->orWhere('prof_vocal_2', $profesorId);
            })->exists();
    }

}
