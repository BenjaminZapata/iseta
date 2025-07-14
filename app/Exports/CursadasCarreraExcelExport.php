<?php

namespace App\Exports;

use App\Models\Asignatura;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class CursadasCarreraExcelExport implements FromCollection, WithHeadings, WithTitle
{
    protected $carrera;
    protected $filtros;

    public function __construct($carrera, $filtros = [])
    {
        $this->carrera = $carrera;
        $this->filtros = $filtros;
    }

    public function collection(): Collection
    {
        $filtros = $this->filtros;

        $anio = isset($filtros['anio']) ? (int) $filtros['anio'] : null;
        $genero = $this->mapGenero($filtros['genero'] ?? null);
        $condicion = $this->mapCondicionToInt($filtros['condicion'] ?? null);

        $asignaturas = Asignatura::whereHas('carrera', fn($q) => $q->where('id', $this->carrera->id))
            ->with(['cursadas.alumno'])
            ->get();

        $rows = [];

        foreach ($asignaturas as $asignatura) {
            foreach ($asignatura->cursadas as $cursada) {
                $alumno = $cursada->alumno;

                if (!$alumno)
                    continue;

                // Filtros manuales
                if (!empty($anio) && $cursada->anio_cursada != $anio)
                    continue;

                if (!is_null($condicion) && $cursada->condicion != $condicion)
                    continue;

                if (!is_null($genero) && (int) $alumno->genero !== $genero)
                    continue;


                $rows[] = [
                    $asignatura->nombre,
                    $alumno->apellidoNombre(),
                    $alumno->dni,
                    $cursada->condicionString(),
                    $cursada->anio_cursada,
                    $alumno->generoString()
                ];
            }
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return ['Asignatura', 'Alumno', 'DNI', 'Condición', 'Año', 'Genero'];
    }

    public function title(): string
    {
        return 'Cursadas';
    }

    protected function mapCondicionToInt($value)
    {
        return match (strtolower($value)) {
            'libre' => 0,
            'regular' => 1,
            'promocion' => 2,
            'equivalencia' => 3,
            'desertor' => 4,
            'itinerante' => 5,
            'oyente' => 6,
            default => null,
        };
    }

    protected function mapGenero($value): ?int
    {
        return match (strtolower($value)) {
            'm', 'masculino' => 1,
            'f', 'femenino' => 2,
            'o', 'otro' => 3,
            default => null,
        };
    }
}


