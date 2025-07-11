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
        $asignaturas = Asignatura::whereHas('carrera', fn($q) => $q->where('id', $this->carrera->id))
            ->with([
                'cursadas' => function ($query) {
                    if (!empty($this->filtros['genero'])) {
                        $query->whereHas('alumno', function ($q) {
                            $q->where('genero', $this->filtros['genero']);
                        });
                    }
                    if (!empty($this->filtros['anio'])) {
                        $query->where('anio_cursada', $this->filtros['anio']);
                    }
                    if (!empty($this->filtros['condicion'])) {
                        $query->where('condicion', $this->filtros['condicion']);
                    }
                },
                'cursadas.alumno'
            ])
            ->get();

        $rows = [];

        foreach ($asignaturas as $asignatura) {
            foreach ($asignatura->cursadas as $cursada) {
                $rows[] = [
                    $asignatura->nombre,
                    $cursada->alumno->apellidoNombre(),
                    $cursada->alumno->dni,
                    $cursada->condicionString(),
                    $cursada->anio_cursada,
                ];
            }
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return ['Asignatura', 'Alumno', 'DNI', 'Condición', 'Año'];
    }

    public function title(): string
    {
        return 'Cursadas';
    }
}
