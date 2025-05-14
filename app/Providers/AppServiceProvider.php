<?php

namespace App\Providers;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Profesor;
use App\Services\Fecha;
use App\Services\Filter;
use App\Services\Form;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View::share('filtergen', new FilterGenerator());
        View::share('filtergen', new Filter());
        View::share('formatoFecha', new Fecha());
        View::share('config', Configuracion::todas());
        View::share('form', new Form());
        View::share('profesorM', new Profesor());
        View::share('alumnoM', new Alumno());
        View::share('carreraM', new Carrera());
        // View::share('profesorM', new Profesor());
        // View::share('profesorM', new Profesor());
    }
}
