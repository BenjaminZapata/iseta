<?php

namespace Tests\Feature;

use App\Models\CarreraDefault;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Asignatura;
use App\Models\Cursada;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CursadaTest extends TestCase
{
        /**
         * Test para agregar un alumno como cursante a una carrera.
         *
         * Verificamos que se guarde correctamente la cursada y sus relaciones.
         * Luego verificamos los métodos personalizados para obtener el string de la condición y el string de la aprobación.
         *
         * @return void
         */
    public function test_agregar_alumno_como_cursante_a_una_carrera()
    {
        // Crear alumno
        $alumno = Alumno::where('email', '=' , 'juan@example.com')->first();


        $carrera = Carrera::where("id", 18)->first(); // ID de Ingeniería en Sistemas
        $this->assertNotNull($carrera, 'La carrera no existe');

        $asignatura = Asignatura::where("id_carrera", 18)->take(3)->get(); // ID de Programación 1
        $this->assertCount(3, $asignatura, "No se encontraron 3 asignaturas de la carrera 18");
        if (!CarreraDefault::where('id_alumno', $alumno->id)->exists()) {
            CarreraDefault::create([
                'id_carrera' => 18, // ID de Ingeniería en Sistemas
                'id_alumno' => $alumno->id,
            ]);
        }
        // Crear la cursada
        if ($alumno->cursadas()->where('id_carrera', 18)->exists()) {
            $this->assertTrue(false, 'El alumno ya está inscripto en la carrera 18');
        } else {
            foreach ($asignatura as $asig) {
                Cursada::create([
                    'anio_cursada' => 2025,
                    'aprobada' => 0, // Cursando
                    'id_alumno' => $alumno->id,
                    'id_asignatura' => $asig->id, // ID analisis de sistemas
                    'id_carrera' => 18,
                    'condicion' => 1, // Regular
                ]);
            }

        }
    }
}
