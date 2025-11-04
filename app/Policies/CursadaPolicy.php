<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Alumno;
use App\Models\Cursada;
use Illuminate\Auth\Access\Response;

class CursadaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Alumno $alumno): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Alumno $alumno, Cursada $cursada): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Alumno $alumno): bool
    {
        return false;
    }

    public function createAdmin(?Admin $admin): Response
    {
        return $admin?->rol === 0 || $admin?->rol === 0 ? Response::allow() : Response::deny('No autorizado.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Alumno $alumno, Cursada $cursada): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Alumno $alumno, Cursada $cursada): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Alumno $alumno, Cursada $cursada): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Alumno $alumno, Cursada $cursada): bool
    {
        return false;
    }
}
