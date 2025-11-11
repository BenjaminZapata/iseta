<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('auth:admin')->only('logout');
    }

    public function loginView(): View
    {
        return view('Admin.Auth.login');
    }

    public function login(Request $request): RedirectResponse
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

        $roles = [
            'regente' => 0,
            'preceptor' => 1,
            'secretario' => 2,
        ];

        $rolSeleccionado = $roles[$request->rol] ?? null;

        if ($admin->rol !== $rolSeleccionado) {
            return back()->withErrors(['rol' => 'Rol incorrecto para este usuario.']);
        }

        if (!Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        Auth::guard('admin')->login($admin);

        // 🔹 Lógica por rol (pero siempre redirige a alumnos.index)
        if ($request->rol === 'regente') {
            return redirect()->route('admin.alumnos.index')
                ->with('success', 'Bienvenido, Regente');
        }

        if ($request->rol === 'preceptor') {
            return redirect()->route('admin.alumnos.index')
                ->with('success', 'Bienvenido, Preceptor');
        }

        if ($request->rol === 'secretario') {
            return redirect()->route('admin.alumnos.index')
                ->with('success', 'Bienvenido, Secretario');
        }

        // fallback por si acaso
        return redirect()->route('admin.alumnos.index')
            ->with('info', 'Sesión iniciada correctamente.');
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
