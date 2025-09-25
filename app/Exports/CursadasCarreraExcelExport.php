<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;
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
        $anio = isset($this->filtros['anio']) && $this->filtros['anio'] !== '' ? (int) $this->filtros['anio'] : null;
        $genero = $this->mapGenero($this->filtros['genero'] ?? null);
        $condicion = $this->mapCondicionToInt($this->filtros['condicion'] ?? null);
        $asignaturaId = $this->filtros['asignatura_id'] ?? null;

        $query = DB::table('cursadas')
            ->join('alumnos', 'cursadas.id_alumno', '=', 'alumnos.id')
            ->join('asignaturas', 'cursadas.id_asignatura', '=', 'asignaturas.id')
            ->where('cursadas.id_carrera', $this->carrera->id);

        if (!empty($anio)) {
            $query->where('cursadas.anio_cursada', $anio);
        }

        if (!is_null($condicion)) {
            $query->where('cursadas.condicion', $condicion);
        }

        if (!is_null($genero)) {
            $query->where('alumnos.genero', $genero);
        }

        if (!empty($asignaturaId)) {
            $query->where('asignaturas.id', $asignaturaId);
        }

        $resultados = $query->select([
            'asignaturas.nombre as asignatura',
            DB::raw("CONCAT(alumnos.apellido, ', ', alumnos.nombre) as alumno"),
            'alumnos.dni',
            'cursadas.condicion',
            'cursadas.anio_cursada',
            'alumnos.genero',
        ])->get();

        $rows = [];

        foreach ($resultados as $r) {
            $rows[] = [
                $r->asignatura,
                $r->alumno,
                $r->dni,
                $this->condicionString($r->condicion),
                $r->anio_cursada,
                $this->generoString($r->genero)
            ];
        }

        return collect($rows);
    }


    public function headings(): array
    {
        return ['Asignatura', 'Alumno', 'DNI', 'Condición', 'Año calendario', 'Género'];
    }

    public function title(): string
    {
        return $this->carrera->nombre;
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

    protected function condicionString($value)
    {
        return match ((int) $value) {
            0 => 'Libre',
            1 => 'Regular',
            2 => 'Promoción',
            3 => 'Equivalencia',
            4 => 'Desertor',
            5 => 'Itinerante',
            6 => 'Oyente',
            default => 'Desconocido',
        };
    }

    protected function generoString($value)
    {
        return match ((int) $value) {
            1 => 'Masculino',
            2 => 'Femenino',
            3 => 'Otro',
            default => 'Desconocido',
        };
    }

}