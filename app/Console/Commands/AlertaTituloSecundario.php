<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alumno;
use App\Models\NotificacionAlumno;
use App\Models\Configuracion;
use App\Models\Egresado;
use Carbon\Carbon;

class AlertaTituloSecundario extends Command
{
  protected $signature = 'alerta:titulo-secundario';

  protected $description = 'Genera notificaciones si el alumno no entregó el título secundario antes de la fecha límite';

  public function handle()
  {
    $fechaLimite = Configuracion::get('fecha_limite_titulo_secundario'); // ejemplo: '2025-08-20'

    $alumnos = Alumno::whereHas('inscripciones', function ($query) {
      $query->where('estado', 'Cursando');
    })
      ->where(function ($query) use ($fechaLimite) {
        $query->whereNull('titulo_secundario')
          ->orWhereDate('vencimiento_titulo_secundario', '<=', $fechaLimite);
      })
      ->get();

    $hoy = Carbon::now()->toDateString();

    foreach ($alumnos as $alumno) {
      $mensaje = 'Falta entregar título secundario';

      // Verificamos si ya existe una notificación similar para hoy
      $existe = NotificacionAlumno::where('id_alumno', $alumno->id)
        ->where('tipo', 'titulo')
        ->where('mensaje', $mensaje)
        ->where('fecha', $hoy)
        ->exists();

      if (!$existe) {
        NotificacionAlumno::create([
          'id_alumno' => $alumno->id,
          'tipo' => 'titulo',
          'mensaje' => $mensaje,
          'fecha' => $hoy,
          'leido' => false,
        ]);
        $this->info("Notificación creada para {$alumno->apellido}, {$alumno->nombre}");
      } else {
        $this->line("Ya existe notificación para {$alumno->apellido}, {$alumno->nombre}");
      }
    }
  }
}
