<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Configuracion;
use App\Models\Cursada;
use App\Models\Egresado;
use App\Models\Examen;
use App\Models\Mesa;
use App\Models\Profesor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PaginasCarganTest extends TestCase
{

    /**
     * A basic feature test example.
     */
    public function test_paginas_admin_cargan(): void{
        // $this->withoutExceptionHandling();

        $response = $this->get('/admin/login');
        $response->assertStatus(200);

        $response = $this->get('/admin/alumnos');
        $response->assertStatus(302);

        Auth::guard('admin')->login(Admin::first());

        $response = $this->get('/admin/login');
        $response->assertStatus(302);

        $response = $this->get('/admin/alumnos');
        $response->assertStatus(200)->assertSee('alumno');

        $response = $this->get('/admin/alumnos/'.Alumno::where('email','test@gmail.com')->first()->id.'/edit');
        $response->assertStatus(200)->assertSee('algunos examanes de alumnos');

        $response = $this->get('/admin/profesores');
        $response->assertStatus(200)->assertSee('profesore');

        $response = $this->get('/admin/profesores/'.Profesor::first()->id.'/edit');
        $response->assertStatus(200)->assertSee('Proximas mesas');

        $response = $this->get('/admin/carreras');
        $response->assertStatus(200)->assertSee('carrera');

        $response = $this->get('/admin/carreras/'.Carrera::first()->id.'/edit');
        $response->assertStatus(200)->assertSee('Agregar asignatura');

        $response = $this->get('/admin/mesas');
        $response->assertStatus(200)->assertSee('mesa');

        $response = $this->get('/admin/mesas/'. Mesa::first()->id.'/edit');
        $response->assertStatus(200)->assertSee('Alumnos inscriptos');

        $response = $this->get('/admin/cursadas');
        $response->assertStatus(200)->assertSee('cursada');

        $response = $this->get('/admin/cursadas/'. Cursada::first()->id.'/edit');
        $response->assertStatus(200)->assertSee('Cursada');

        $response = $this->get('/admin/inscriptos');
        $response->assertStatus(200)->assertSee('inscripcion');

        $response = $this->get('/admin/inscriptos/'. Egresado::first()->id.'/edit');
        $response->assertStatus(200)->assertSee('Ficha inscripto');

        $response = $this->get('/admin/admins');
        $response->assertStatus(200)->assertSee('Crear');

        $response = $this->get('/admin/config');
        $response->assertStatus(200)->assertSee('habiles');

        $response = $this->get('/admin/dias-habiles');
        $response->assertStatus(200)->assertSee('Enero');

        $response = $this->get('/admin/examenes/'. Examen::first()->id.'/edit');
        $response->assertStatus(200)->assertSee('Ficha examen');

        $response = $this->get('/admin/cursadas/'. Carrera::find(15)->primeraAsignatura()->id);
        $response->assertStatus(200)->assertSee('Cargar resultados de cursadas');

        $response = $this->get('/admin/mesas-dual/'. Carrera::find(15)->primeraAsignatura()->id);
        $response->assertStatus(200)->assertSee('Crear mesas de primer y segundo llamado');

        $this->get('item');

    }

    public function test_modo_seguro_funciona(){
        Auth::guard('admin')->login(Admin::first());

        $modo = Configuracion::get('modo_seguro');
        $response = $this->get('/admin/config/modoseguro');

        $response->assertStatus(302);

        if(!($modo != Configuracion::get('modo_seguro'))){
            $this->assertFalse(true);
        }
    }

    public function test_paginas_alumnos_cargan(): void
    {
        // $this->withoutExceptionHandling();

        $response = $this->get('/alumno/login');
        $response->assertStatus(200)->assertSee('Inicio de s');

        $response = $this->get('alumno/registro');
        $response->assertStatus(200)->assertSee('Registrate');

        $response = $this->get('/alumno/cursadas');
        $response->assertStatus(302);

        Auth::guard('web')->login(Alumno::where('email','test@gmail.com')->first());

        $response = $this->get('/alumno/login');
        $response->assertStatus(302);

        $response = $this->get('/alumno/registro');
        $response->assertStatus(302);

        $response = $this->get('/alumno/cursadas');
        $response->assertStatus(200)->assertSee('Mis');

        $response = $this->get('/alumno/examenes');
        $response->assertStatus(200)->assertSee('Examenes');

        $response = $this->get('/alumno/inscripciones');
        $response->assertStatus(200)->assertSee('Inscripciones');

        $response = $this->get('/alumno/rematriculacion');

        $hoy = Carbon::now();

        $inicio = Carbon::parse(Configuracion::get('fecha_inicial_rematriculacion'));
        $final = Carbon::parse(Configuracion::get('fecha_final_rematriculacion'));

        // Compara las fechas
        if ($final->greaterThan($hoy) && $inicio->lessThan($hoy)) {
           $response->assertStatus(200);
           $response->assertStatus(200)->assertSee('Matricular');
        }else $response->assertStatus(302);

        $response = $this->get('/alumno/info');
        $response->assertStatus(200)->assertSee('Perfil');

    }

}
