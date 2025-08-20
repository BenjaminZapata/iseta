<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Alumno;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Notifications\FaltaTituloSecundario;

class NotificarFaltanteTitulo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notificar-faltante-titulo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alerta a los administradores sobre alumnos que no han entregado su título secundario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cuantos = Alumno::where('titulo_secundario', 0)
            ->where('fecha_titulo_secundario', '<=', Carbon::now())
            ->count();

        if ($cuantos > 0) {
            $admins = Admin::all();

            foreach ($admins as $admin) {
                // Solo notificar si no existe ya una notificación con ese número hoy
                $yaNotificado = $admin->notifications()
                    ->where('type', 'App\Notifications\FaltaTituloSecundario')
                    ->whereNull('read_at')
                    ->exists();


                if (!$yaNotificado) {
                    $admin->notify(new FaltaTituloSecundario($cuantos));
                    $this->info("Notificado a {$admin->username} sobre {$cuantos} alumnos sin título secundario.");
                } else {
                    $this->info("Ya se notificó hoy a {$admin->username}.");
                }
            }
        }
    }
}
