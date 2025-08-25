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

        $route = request()->route();
        $name = Route::currentRouteName() ?? '';
        $path = request()->path();

        /**
         * 1) Caso específico: admin.cursadas.edit
         *    Decide destino según origen
         */
        if ($name === 'admin.cursadas.edit') {
            $prev = url()->previous();

            // Si venís desde un alumno → volver al alumno
            if (preg_match('#/admin/alumnos/(\d+)/edit#', $prev, $m)) {
                $this->url = route('admin.alumnos.edit', ['alumno' => $m[1]]);
                return;
            }

            // Si no, volver al index de cursadas
            $this->url = route('admin.cursadas.index');
            return;
        }

        if ($name === 'admin.examenes.edit') {
            $prev = url()->previous();

            // Si venís desde un alumno
            if (preg_match('#/admin/alumnos/(\d+)/edit#', $prev, $m)) {
                $this->url = route('admin.alumnos.edit', ['alumno' => $m[1]]);
                return;
            }

            // Si venís desde mesas
            if (preg_match('#/admin/mesas/(\d+)/edit#', $prev, $m)) {
                $this->url = route('admin.mesas.edit', ['mesa' => $m[1]]);
                return;
            }

            // Si no encuentra origen claro → seguir con lógica existente
            $this->url = route('admin.alumnos.index');
            return;
        }

        if ($name === 'admin.asignaturas.edit') {
            $prev = url()->previous();

            // Si venís del index de asignaturas
            if (preg_match('#/admin/asignaturas$#', $prev)) {
                $this->url = route('admin.asignaturas.index');
                return;
            }

            // Si venís del edit de carreras
            if (preg_match('#/admin/carreras/(\d+)/edit#', $prev, $m)) {
                $this->url = route('admin.carreras.edit', ['carrera' => $m[1]]);
                return;
            }

            // Si no reconoce el origen, usar index de asignaturas por defecto
            $this->url = route('admin.asignaturas.index');
            return;




            // Si no encuentra origen claro → seguir con lógica existente
        }


        /**
         * 2) Mapa hijo → padre
         */
        $mapa = [
            '#^admin/mesas-dual/(\d+)(/.*)?$#' => ['admin.carreras.edit', ['carrera' => 1]],
            '#^admin/asignaturas/(\d+)(/.*)?$#' => ['admin.carreras.edit', ['carrera' => 1]],
            '#^admin/alumnos/(\d+)/cursadas(/.*)?$#' => ['admin.alumnos.edit', ['alumno' => 1]],
        ];

        foreach ($mapa as $regex => [$routeName, $paramsMap]) {
            if (preg_match($regex, $path, $matches)) {
                $params = [];
                foreach ($paramsMap as $key => $idx) {
                    $params[$key] = $matches[$idx];
                }
                $this->url = route($routeName, $params);
                return;
            }
        }

        /**
         * 3) Edit genérico → index del recurso
         */
        if (request()->is('admin/*/*/edit')) {
            $parts = explode('.', $name);
            if (count($parts) >= 2) {
                $base = $parts[0] . '.' . $parts[1];
                $candidate = $base . '.index';
                if (Route::has($candidate)) {
                    $this->url = route($candidate);
                    return;
                }
            }
        }

        /**
         * 4) Fallback final
         */
        $this->url = route('admin.alumnos.index');
    }

    public function render()
    {
        return view('components.btn-cancelar');
    }
}