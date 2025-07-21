<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alumno;
use Carbon\Carbon;
use App\Models\Configuracion;

class AlertaTituloSecundario extends Command
{
 protected $signature = 'alerta:titulo-secundario';

 protected $description = 'Actualiza el titulo_secundario de alumnos si pasó la fecha límite';

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

  foreach ($alumnos as $alumno) {
   echo "Alumno pendiente: {$alumno->apellido}, {$alumno->nombre}\n";
   // Acá podrías también crear una notificación o guardar el registro
  }
 }

}
