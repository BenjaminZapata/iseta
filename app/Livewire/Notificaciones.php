<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Notificaciones extends Component
{
    public $notificaciones;

    public function mount()
    {
        $this->notificaciones = Auth::user()->notifications->all();
    }

    public function marcarComoLeida($id)
    {
        $notificacion = Auth::user()->notifications()->find($id);

        if ($notificacion) {
            $notificacion->markAsRead();
            // Recargamos solo la lista
            $this->notificaciones = Auth::user()->notifications;
        }
    }

    public function render()
    {
        foreach ($this->notificaciones as $notificacion) {
            Log::info($notificacion);
        }

        return view('livewire.notificaciones');
    }
}
