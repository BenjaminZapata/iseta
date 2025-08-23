<?php

namespace App\Repositories\Admin;

use App\Models\Asignatura;
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

    /**
     * Obtener cursantes de una asignatura.
     */
    public function cursantes(int $id)
    {
        $asignatura = $this->find($id);
        return $asignatura ? $asignatura->cursantes() : collect();
    }
    public function filter(array $filters, int $perPage = 15)
{
    $query = $this->model->query();

    // 🔹 Si viene un ID de asignatura, devolver solo esa
    if (!empty($filters['filter_asignatura_id']) && $filters['filter_asignatura_id'] != 0) {
        return $query->where('id', $filters['filter_asignatura_id'])
                     ->paginate($perPage);
    }

    // Filtrar por nombre
    if (!empty($filters['nombre'])) {
        $query->where('nombre', 'like', '%' . $filters['nombre'] . '%');
    }

    // Filtrar por año
    if (!empty($filters['filter_anio']) && $filters['filter_anio'] !== 'Todos') {
        $anio = (int)$filters['filter_anio'] - 1; // porque tu model decrementa el año
        $query->where('anio', $anio);
    }

    // Filtrar por tipo de módulo
    if (!empty($filters['tipo_modulo'])) {
        $query->where('tipo_modulo', $filters['tipo_modulo']);
    }

    // Filtrar por carga horaria
    if (!empty($filters['filter_carga_horaria']) && $filters['filter_carga_horaria'] !== 'Cualquiera') {
        switch ($filters['filter_carga_horaria']) {
            case 'Menos de 10 hs':
                $query->where('carga_horaria', '<', 10)
                      ->orderBy('carga_horaria', 'desc');
                break;
            case '10 a 20 hs':
                $query->whereBetween('carga_horaria', [10, 20])
                      ->orderBy('carga_horaria', 'desc');
                break;
            case 'Más de 20 hs':
                $query->where('carga_horaria', '>', 20)
                      ->orderBy('carga_horaria', 'desc');
                break;
        }
    }

    // Filtrar por carrera
    if (!empty($filters['filter_carrera_id']) && $filters['filter_carrera_id'] != 0) {
        $query->whereHas('carrera', function ($q) use ($filters) {
            $q->where('id', $filters['filter_carrera_id']);
        });
    }

    return $query->orderBy('nombre')->paginate($perPage);
}


}
