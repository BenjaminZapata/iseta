<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminSeguridadController extends Controller
{
    function vista()
    {
        return \view('Admin.seguridad');
    }

    function editar()
    {
    }
}
