<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class BtnCancelar extends Component
{
    public $ruta;

    public function __construct($ruta = null)
    {
        if ($ruta) {
            // Si te pasaron una ruta a mano, usala
            $this->ruta = $ruta;
        } else {
            // Si no, deducila automáticamente desde la ruta actual
            $nombreRutaActual = Route::currentRouteName(); // e.g. admin.alumnos.edit

            // Obtenemos la parte 'admin.alumnos'
            $base = implode('.', array_slice(explode('.', $nombreRutaActual), 0, 2));

            // Armamos la ruta 'admin.alumnos.index'
            $this->ruta = $base . '.index';
        }
    }

    public function render()
    {
        return view('components.btn-cancelar');
    }
}
