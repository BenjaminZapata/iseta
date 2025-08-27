<?php

namespace App\Repositories\Admin;

use App\Models\Asignatura;
use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\Collection;

class AsignaturaRepository
{
    protected Asignatura $model;

    public function __construct(Asignatura $model)
    {
        $this->model = $model;
    }

    /**
     * Obtener todas las asignaturas.
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Buscar por ID.
     */
    public function find(int $id): ?Asignatura
    {
        return $this->model->find($id);
    }

    /**
     * Crear una nueva asignatura.
     */
    public function create(array $data): Asignatura
    {
        return $this->model->create($data);
    }

    /**
     * Actualizar asignatura.
     */
    public function update(int $id, array $data): ?Asignatura
    {
        $asignatura = $this->find($id);
        if ($asignatura) {
            $asignatura->update($data);
        }
        return $asignatura;
    }

    /**
     * Eliminar asignatura.
     */
    public function delete(int $id): bool
    {
        $asignatura = $this->find($id);
        return $asignatura ? $asignatura->delete() : false;
    }

    /**
     * Obtener asignaturas con sus profesores.
     */
    public function withProfesores(): Collection
    {
        return $this->model->with('profesor')->get();
    }

    /**
     * Obtener asignaturas con sus carreras.
     */
    public function withCarreras(): Collection
    {
        return $this->model->with('carrera')->get();
    }

    /**
     * Listado para dropdown (id => nombre).
     */
    public function dropdown(): array
    {
        return $this->model->orderBy('nombre')->pluck('nombre', 'id')->toArray();
    }

    public function Desvincular(int $asignaturaId, int $carreraId): bool
{
    Log::info('Desvincular iniciado', [
        'asignaturaId' => $asignaturaId,
        'carreraId' => $carreraId
    ]);

    $asignatura = $this->find($asignaturaId);

    if (!$asignatura) {
        Log::warning('Asignatura no encontrada', ['asignaturaId' => $asignaturaId]);
        return false;
    }

    // Revisamos qué carreras tiene la asignatura
    $carreras = $asignatura->carrera()->pluck('id_carrera')->toArray();
    Log::info('Carreras actuales de la asignatura', $carreras);

    if (!in_array($carreraId, $carreras)) {
        Log::warning('La carrera no está vinculada a la asignatura', [
            'carreraId' => $carreraId
        ]);
        return false;
    }

    // Intentamos desvincular
    $deleted = $asignatura->carrera()
        ->newPivotStatement()
        ->where('id_asignatura', $asignaturaId)
        ->where('id_carrera', $carreraId)
        ->delete();

    Log::info('Filas eliminadas del pivot', ['deleted' => $deleted]);

    return $deleted > 0;
}




    /**
     * Obtener cursantes de una asignatura.
     */
    public function cursantes(int $id)
    {
        $asignatura = $this->find($id);
        return $asignatura ? $asignatura->cursantes() : collect();
    }

    /**
     * Filtrar asignaturas según los filtros.
     */
   public function filter(array $filters, int $perPage = 15)
{
    $query = $this->model->query()->with('carrera');

    // 🔹 Si viene un ID de asignatura, devolver solo esa
    if (!empty($filters['filter_asignatura_id']) && $filters['filter_asignatura_id'] != 0) {
        return $query->where('id', $filters['filter_asignatura_id'])
                     ->paginate($perPage);
    }

    // Año (dropdown)
    if (!empty($filters['filter_anio']) && $filters['filter_anio'] !== 'Todos' && $filters['filter_anio'] != 0) {
        $anio = (int)$filters['filter_anio'] - 1; // en BD arranca en 0
        $query->where('anio', $anio);
    }

    // Tipo de módulo
    if (!empty($filters['tipo_modulo'])) {
        $query->where('tipo_modulo', $filters['tipo_modulo']);
    }

    // Carga horaria
    if (!empty($filters['filter_carga_horaria']) && $filters['filter_carga_horaria'] !== 'Cualquiera') {
        switch ($filters['filter_carga_horaria']) {
            case 'Menos de 10 hs':
                $query->where('carga_horaria', '<', 10);
                break;
            case '10 a 20 hs':
                $query->whereBetween('carga_horaria', [10, 20]);
                break;
            case 'Más de 20 hs':
                $query->where('carga_horaria', '>', 20);
                break;
        }
    }

    // Carrera
    if (!empty($filters['filter_carrera_id']) && $filters['filter_carrera_id'] != 0) {
        $query->whereHas('carrera', function ($q) use ($filters) {
            $q->where('id', $filters['filter_carrera_id']);
        });
    }

    // 🔹 Field dinámico (search box)
    if (!empty($filters['filter_search_box']) && !empty($filters['filter_field'])) {
        $field = $filters['filter_field'];
        $value = $filters['filter_search_box'];
        $valor = mb_strtolower(trim($value));

        if ($field === 'nombre' || $field === 'carga_horaria') {
            $query->where($field, 'like', "%$value%");
        }

        if ($field === 'anio') {
            // Mapear textos posibles a número de año
            $map = [
                '1' => 1, '1er' => 1, 'primer' => 1, 'primero' => 1, '1ro' => 1,
                '2' => 2, '2do' => 2, 'segundo' => 2,
                '3' => 3, '3ro' => 3, 'tercero' => 3,
                '4' => 4, '4to' => 4, 'cuarto' => 4,
                '5' => 5, '5to' => 5, 'quinto' => 5,
            ];

            $anio = null;
            foreach ($map as $pattern => $numero) {
                if (str_contains($valor, $pattern)) {
                    $anio = $numero;
                    break;
                }
            }

            if ($anio) {
                $query->where('anio', $anio - 1); // BD empieza en 0
            }
        }

        if ($field === 'carrera') {
            $query->whereHas('carrera', function ($q) use ($value) {
                $q->where('nombre', 'like', "%$value%");
            });
        }
    }

    return $query->orderBy('nombre')->paginate($perPage);
}

}

