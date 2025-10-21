<?php

namespace App\Http\Controllers;

use App\Mail\RestablecerMail;
use App\Models\Alumno;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class PasswordResetController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /* ========================
     * ALUMNOS
     * ======================== */

    public function vista()
    {
        return view('Alumnos.Reset-password.reset');
    }

    public function mail(Request $request)
    {
        $alumno = Alumno::where('email', $request->email)->first();

        if (!$alumno) {
            return back()->with('error', 'No hay ningún alumno con este correo');
        }

        $token = rand(100000, 999999);

        Session::put('__alumno_restablecer_token', $token);
        Session::put('__alumno_restablecer_mail', $request->email);

        Mail::to($request->email)->send(new RestablecerMail($token));

        return view('Alumnos.Reset-password.ingreso-token');
    }

    public function validarToken(Request $request)
    {
        $token = Session::get('__alumno_restablecer_token');
        $mail = Session::get('__alumno_restablecer_mail');

        if (!$token || !$mail) {
            return back()->with('error', 'Hemos perdido el mail o el token. Inténtalo de nuevo.');
        }

        if ($token != $request->token) {
            return back()->with('error', 'Token incorrecto. Se ha enviado un nuevo token');
        }

        $alumno = Alumno::where('email', $mail)->first();

        if (!$alumno || $alumno->password == 0) {
            return redirect()->route('alumno.registro')->with('error', 'No estás registrado');
        }

        $alumno->password = bcrypt($request->password);
        $alumno->save();

        Session::forget('__alumno_restablecer_token');
        Session::forget('__alumno_restablecer_mail');

        return redirect()->route('alumno.login')->with('mensaje', 'Se ha restablecido tu contraseña');
    }

    /* ========================
     * PROFESORES
     * ======================== */

    public function vistaProfe()
    {
        return view('Profesores.Reset-password.reset');
    }

    public function mailProfe(Request $request)
    {
        $profesor = Profesor::where('email', $request->email)->first();

        if (!$profesor) {
            return back()->with('error', 'No hay ningún profesor con este correo');
        }

        $token = rand(100000, 999999);

        Session::put('__profesor_restablecer_token', $token);
        Session::put('__profesor_restablecer_mail', $request->email);

        Mail::to($request->email)->send(new RestablecerMail($token));

        return view('Profesores.Reset-password.ingreso-token');
    }

    public function validarTokenProfe(Request $request)
    {
        $token = Session::get('__profesor_restablecer_token');
        $mail = Session::get('__profesor_restablecer_mail');

        if (!$token || !$mail) {
            return back()->with('error', 'Hemos perdido el mail o el token. Inténtalo de nuevo.');
        }

        if ($token != $request->token) {
            return back()->with('error', 'Token incorrecto. Se ha enviado un nuevo token');
        }

        $profesor = Profesor::where('email', $mail)->first();

        if (!$profesor || $profesor->password == 0) {
            return redirect()->route('profesor.register')->with('error', 'No estás registrado');
        }

        $profesor->password = bcrypt($request->password);
        $profesor->save();

        Session::forget('__profesor_restablecer_token');
        Session::forget('__profesor_restablecer_mail');

        return redirect()->route('profesor.login')->with('mensaje', 'Se ha restablecido tu contraseña');
    }
}
