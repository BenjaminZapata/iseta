<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{

    /*
     | ---------------------------------------------
     | Middleware de administrador, excepto el login
     | ---------------------------------------------
    */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('auth:admin')->only('logout');
    }

    /**
     * Vista Logueo de administrador
     * @return \Illuminate\View\View
     */
    public function loginView(): View{
        return view(view: 'Admin.Auth.login');
    }

    /**
     * Valida las credenciales del administrador y loguea al mismo
     *
     * @param AdminLoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(AdminLoginRequest $request): RedirectResponse
    {

        $validateData = $request->validated();

        $usernameGiven = $validateData['username'];
        $passwordGiven = $validateData['password'];
        // Busca el administrador en la base de datos
        $admin = Admin::where('username', $usernameGiven)->first();

        // Verifica si el administrador existe y si la contraseña es correcta
        if (!$admin || !Hash::check($passwordGiven, $admin->password)) {
            // Si las credenciales son incorrectas, redirige al login con un mensaje de error
            return redirect()->route('admin.login')->with('error', 'Credenciales incorrectas');
        }

        // Loguea al administrador
        Auth::guard('admin')->login($admin);

        // Redirige al listado de alumnos
        return redirect()->route('admin.alumnos.index');
    }

    /**
     * cierra sesion del administrador
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(): RedirectResponse{
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
