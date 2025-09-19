<?php

namespace App\Repositories\Admin;

use App\Models\Configuracion;
use App\Models\Profesor;

class ProfesorRepository
{

    public $config;
    public $availableFiels = ['profesor', 'dni', 'email', 'ciudad', 'telefono1'];


    public function __construct()
    {
        $this->config = Configuracion::todas();
    }

    public function index($request)
    {
        // Query base para obtener IDs de profesores según filtros
        $idsQuery = Profesor::select('profesores.id');


        // Filtro de búsqueda
        if ($request->has('filter_search_box') && $request->input('filter_search_box') != '') {
            $field = trim(strtolower($request->input('filter_field'))); // eliminar espacios y normalizar
            $search = $request->input('filter_search_box');

            if (in_array($field, array_map('strtolower', $this->availableFiels))) {
                if ($field == 'profesor') {
                    // Buscar por nombre completo o email
                    $word = str_replace(' ', '%', $search);
                    $idsQuery->whereRaw(
                        "(CONCAT(profesores.nombre,' ',profesores.apellido) LIKE ? OR profesores.email LIKE ?)",
                        ["%$word%", "%$word%"]
                    );
                } elseif ($field == 'dni') {
                    // Normalizar el DNI eliminando puntos, guiones o espacios
                    $search = preg_replace('/\D/', '', $search);
                    $idsQuery->where('profesores.dni', 'LIKE', "%$search%");
                } else {
                    // Otros campos: email, ciudad, telefono1
                    $idsQuery->where("profesores.$field", 'LIKE', "%$search%");
                }
            }
        }

        // Obtener IDs distintos que cumplen los filtros
        $ids = $idsQuery->distinct()->pluck('id');

        // Query final con registros completos
        $query = Profesor::select('profesores.*')
            ->whereIn('profesores.id', $ids)
            ->orderBy('apellido')
            ->orderBy('nombre');

        // Retornar paginación según configuración
        return $query->paginate($this->config['filas_por_tabla']);
    }
}
