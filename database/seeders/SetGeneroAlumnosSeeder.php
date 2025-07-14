<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;

class SetGeneroAlumnosSeeder extends Seeder
{
    protected $nombresFemeninos = [
        'maría',
        'ana',
        'luisa',
        'laura',
        'paula',
        'sofia',
        'camila',
        'valentina',
        'julieta',
        'catalina',
        'martina',
        'carla',
        'lucia',
        'florencia',
        'daniela',
        'ayelen',
        'giselle',
        'claudia',
        'yesica',
        'soledad',
        'sol',
        'emilia',
        'mirian',
        'marilyn',
        'agostina',
        'milagros',
        'brisa',
        'magali',
        'anyelen',
        'eliana'
    ];

    protected $nombresMasculinos = [
        'juan',
        'carlos',
        'josé',
        'luis',
        'pedro',
        'matías',
        'lucas',
        'tomás',
        'agustín',
        'santiago',
        'martín',
        'nicolás',
        'emiliano',
        'federico',
        'alexis',
        'esteban',
        'jonathan',
        'pablo',
        'diego',
        'ramiro',
        'claudio',
        'enzo',
        'lautaro',
        'javier',
        'jeremias',
        'dario',
        'franco',
        'maximiliano',
        'gonzalo',
        'leandro',
        'rodrigo',
        'damián',
        'cristian',
        'alejandro',
        'andrés',
        'gustavo',
        'marcos',
        'sebastián',
        'martín',
        'fabián',
        'hernán',
        'ignacio',
        'julio',
        'oscar',
        'ricardo',
        'roberto',
        'salvador'
    ];

    public function run(): void
    {
        $alumnos = Alumno::all();
        $actualizados = 0;

        foreach ($alumnos as $alumno) {
            $nombre = strtolower(trim($alumno->nombre));
            $genero = null;

            foreach ($this->nombresFemeninos as $f) {
                if (str_starts_with($nombre, $f)) {
                    $genero = 2; // femenino
                    break;
                }
            }

            if (is_null($genero)) {
                foreach ($this->nombresMasculinos as $m) {
                    if (str_starts_with($nombre, $m)) {
                        $genero = 1; // masculino
                        break;
                    }
                }
            }

            if (!is_null($genero)) {
                $alumno->genero = $genero;
                $alumno->save();
                $actualizados++;
            }
        }

        echo "✅ Alumnos actualizados con género: {$actualizados}\n";
    }
}
