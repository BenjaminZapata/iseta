<?php

namespace Tests\Feature;

use App\Models\Alumno;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AlumnoInscripcionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_informacion_mostrada_es_correcta(): void
    {


        Auth::guard('web')->login(Alumno::where('email','juan@example.com')->first());

         $response = $this->get('/alumno/inscripciones');

         $response->assertStatus(200)
             ->assertSee('Test_asignatura_4')
             ->assertSee('Debes correlativas')
             ->assertSee('Test_asignatura_mismo_anio_3')
             ->assertSee('Test_asignatura_4')
             ->assertSee('No disponible')
             ->assertSee('Inscribirme')
             ->assertSee('Desinscribirme')
             ->assertDontSee('Test_asignatura_1')
             ->assertDontSee('No hay llamado');

    }
}
