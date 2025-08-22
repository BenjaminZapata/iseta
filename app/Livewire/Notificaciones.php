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
        $this->fetchNotificaciones();
    }

    public function fetchNotificaciones()
    {
        $this->notificaciones = Auth::user()->notifications()->latest()->take(20)->get();
    }

    public function marcarComoLeida($id)
    {
        $notificacion = Auth::user()->notifications()->find($id);

        if ($notificacion && !$notificacion->read_at) {
            $notificacion->markAsRead();
        }

        $this->fetchNotificaciones();
    }

    public function borrarNotificacion($id)
    {
        $notificacion = Auth::user()->notifications()->find($id);

        if ($notificacion) {
            $notificacion->delete();
        }

        $this->fetchNotificaciones();
    }

    public function borrarTodas()
    {
        Auth::user()->notifications()->delete();

        $this->fetchNotificaciones();
    }

    public function render()
    {
        return view('livewire.notificaciones');
    }
}