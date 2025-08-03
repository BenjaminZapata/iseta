<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asignatura;

class AsignaturaController extends Controller
{
    public function index()
    {
        // Solo asignaturas que tienen al menos una carrera
        $asignaturas = Asignatura::whereHas('carrera')->paginate(10);

        return view('Regente.Asignaturas.index', compact('asignaturas'));
    }
}
