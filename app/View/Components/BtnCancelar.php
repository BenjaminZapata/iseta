<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;
use App\Models\Cursada;


class BtnCancelar extends Component
{
    public string $url;

    public function __construct(string $parent = null)
    {
        // 0) Parent explícito manda
        if ($parent) {
            $this->url = $parent;
            return;
        }

        // 1) Primero intento con la nueva lógica centralizada
        $origen = \App\Services\NavegacionService::detectarOrigen();
        if ($origen && !empty($origen['url'])) {
            $this->url = $origen['url'];
            return;
        }

        // 2) Si el servicio no detecta nada → usar la lógica que ya tenías
        $this->url = $this->resolverUrlConLogicaOriginal();
    }

    // Mover todo tu bloque actual del constructor a este método privado:
    private function resolverUrlConLogicaOriginal(): string
    {
        $route = request()->route();
        $name  = \Illuminate\Support\Facades\Route::currentRouteName() ?? '';
        $path  = request()->path();

        // ... ⬅️ Aquí todo tu código anterior de detección paso a paso
        // pero devolviendo un string con la URL final

        return route('admin.alumnos.index'); // fallback
    }


    public function render()
    {
        return view('components.btn-cancelar');
    }
}
