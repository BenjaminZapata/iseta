<?php

namespace App\Repositories\Admin;

use App\Models\Admin;
use App\Models\Configuracion;

class AdminRepository
{
    protected $config;
    protected $availableFields = ['username', 'rol']; // campos disponibles para filtro

    public function __construct()
    {
        $this->config = Configuracion::todas(); // toma la configuración
    }

    /**
     * Obtener administradores filtrando por username y rol (paginado según configuración)
     *
     * @param string|null $username
     * @param int|string|null $rol
     */
  public function getAdmins($username = null, $rol = null)
{
    $query = Admin::query();

    if ($username) {
        // Limitar el username a 50 caracteres máximo
        $username = substr($username, 0, 50);
        $query->where('username', 'LIKE', "%{$username}%");
    }

    if ($rol !== null && $rol !== '') {
        $query->where('rol', $rol);
    }

    $filasPorTabla = (int) ($this->config['filas_por_tabla'] ?? 15);
    if ($filasPorTabla <= 0) {
        $filasPorTabla = 15;
    }

    return $query->orderBy('username')->paginate($filasPorTabla);
}

}
