<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alumno;
use App\Models\NotificacionAlumno;
use Carbon\Carbon;

class AlertaTituloSecundario extends Command
{
  protected $signature = 'alerta:titulo-definitivo';
  protected $description = 'Genera notificaciones para alumnos que entregaron constancia pero no el título definitivo antes del 30 de agosto';

  public function handle()
  {
    $fechaLimite = Carbon::create(null, 8, 30)->toDateString(); // 30 de agosto
    $hoy = Carbon::now()->toDateString();

    // Solo alumnos cursando que entregaron constancia (2), pero NO el título (debería ser 1 o algo más)
    $alumnos = Alumno::whereHas('inscripciones', function ($q) {
      $q->where('estado', 'Cursando');
    })
      ->where('titulo_secundario', 2) // entregó constancia, pero no el título
      ->whereDate('updated_at', '<=', $fechaLimite) // se entregó hace tiempo, ya pasó el plazo
      ->get();

    $mensaje = 'No se ha entregado el título secundario definitivo antes de la fecha límite (30/08)';

    foreach ($alumnos as $alumno) {
      $existe = NotificacionAlumno::where('id_alumno', $alumno->id)
        ->where('tipo', 'titulo_definitivo')
        ->where('mensaje', $mensaje)
        ->where('fecha', $hoy)
        ->exists();

      if (!$existe) {
        NotificacionAlumno::create([
          'id_alumno' => $alumno->id,
          'tipo' => 'titulo_definitivo',
          'mensaje' => $mensaje,
          'fecha' => $hoy,
          'leido' => false,
        ]);
        $this->info("Notificación creada para: {$alumno->apellido}, {$alumno->nombre}");
      } else {
        $this->line("Ya existe notificación para: {$alumno->apellido}, {$alumno->nombre}");
      }
    }
  }
}
