<?php

namespace Tests\Feature;

use App\Models\Alumno;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AlumnoCrearTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_se_puede_crear_un_alumno()
    {
        $datos = [
            'dni' => '12345678',
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'fecha_nacimiento' => '2000-01-01',
            'ciudad' => 'Buenos Aires',
            'calle' => 'San Martín',
            'casa_numero' => '123',
            'dpto' => 'A',
            'piso' => '2',
            'estado_civil' => 0,
            'email' => 'juan@example.com',
            'nombre_institucion_secundario' => 'Escuela Nacional',
            'titulo_anterior' => 'Bachiller',
            'becas' => 0,
            'telefono1' => '123456789',
            'telefono2' => '987654321',
            'telefono3' => '1122334455',
            'codigo_postal' => '1000',
            'password' => bcrypt('mi_clave_segura'),
        ];

        $alumno = Alumno::create($datos);

        // Forzamos el campo verificado si no es fillable directamente
        $alumno->verificado = 1;
        $alumno->save();

        $this->assertDatabaseHas('alumnos', [
            'dni' => '12345678',
            'email' => 'juan@example.com',
            'verificado' => 1,
        ]);

        // Opcional: Verificamos que el password esté hasheado correctamente
        $this->assertTrue(password_verify('mi_clave_segura', $alumno->password));
    }
}
