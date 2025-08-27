<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    /**
     * Lista de elementos del breadcrumb.
     * Cada uno debe tener 'label' y 'url'.
     */
    public array $breadcrumbs;

    /**
     * Crear una nueva instancia del componente.
     *
     * @param array $breadcrumbs
     */
    public function __construct(array $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * Obtener la vista del componente.
     */
    public function render()
    {
        return view('components.breadcrumb');
    }
}
