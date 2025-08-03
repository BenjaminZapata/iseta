<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Http\Requests\AdminLoginRequest;
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
    public function loginView(): View
    {
        return view(view: 'Admin.Auth.login');
    }

    /**
     * Valida las credenciales del administrador y loguea al mismo
     *
     * @param AdminLoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'rol' => 'required',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin) {
            return back()->withErrors(['username' => 'El usuario no existe.']);
        }

        // Validar rol seleccionado
        $roles = [
            'regente' => 0,
            'preceptor' => 1,
            'secretario' => 2,
        ];

        $rolSeleccionado = $roles[$request->rol] ?? null;

        if ($admin->rol !== $rolSeleccionado) {
            return back()->withErrors(['rol' => 'Rol incorrecto para este usuario.']);
        }

        // Validar contraseña
        if (!\Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        // Iniciar sesión con el guard 'admin'
        Auth::guard('admin')->login($admin);

        return match ($request->rol) {
            'preceptor' => redirect()->route('preceptor.alumnos.index'),
            'regente' => redirect()->route('regente.alumnos.index'),
            'secretario' => redirect()->route('admin.secretario.dashboard'),
            default => redirect()->route('admin.alumnos.index'),
        };
    }



    /**
     * cierra sesion del administrador
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
