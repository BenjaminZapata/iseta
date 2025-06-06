<?php

namespace App\Http\Controllers;

use App\Mail\VerificacionEmail;
use App\Models\Alumno;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

/**
 *
 * Verificacion de mail
 *
 */

class MailVerifController extends Controller
{

    /**
     * envia el mail al usuario con el token de autorizacion
     */
    function enviarMail(){

        $token = rand(100000,999999);

        // el token se guarda en la sesion
        Session::put('__alumno_verificacion_token', $token);

        Mail::to(Auth::user())->send(new VerificacionEmail($token));

        return redirect() -> route('token.ingreso') -> with('mensaje','Se ha enviado el correo');
    }

    /**
     * vista para ingresar el token
     * tambien incluye un boton para enviar el mail cuantas veces sea necesario
     */
    function ingresarTokenView(){
        return view('Alumnos.Mail.ingresoToken');
    }

    /**
     * verificar que el token ingresado sea correcto
     * si lo es verifica su cuenta
     */
    function verificarToken(Request $request){
        $alumno = Auth::user();
        $token = Session::get('__alumno_verificacion_token');

        if($token && $token == $request->token){
            $alumno->verificar();
            $request->session()->forget('__alumno_verificacion_token');
            return redirect()->route('alumno.info')->with('mensaje','estas verificado');
        }

        return redirect()->route('alumno.info')->with('error','token incorrecto o no valido');
    }

    function enviarMailProfe(){
        $token = rand(100000,999999);

        // el token se guarda en la sesion
        Session::put('__profe_verificacion_token', $token);

        Mail::to(Auth::guard('profesor')->user())->send(new VerificacionEmail($token));

        return redirect() -> route('token.ingreso.profe') -> with('mensaje','Se ha enviado el correo');
    }

    /**
     * vista para ingresar el token
     * tambien incluye un boton para enviar el mail cuantas veces sea necesario
     */
    function ingresarTokenViewProfe(){
        return view('Profesores.Mail.ingresoToken');
    }

    /**
     * verificar que el token ingresado sea correcto
     * si lo es verifica su cuenta
     */
    function verificarTokenProfe(Request $request){

        $profe = Profesor::find(Auth::guard('profesor')->id() );

        $token = Session::get('__profe_verificacion_token');

        if($token && $token == $request->token){
            $profe->verificar();
            $request->session()->forget('__alumno_verificacion_token');
            return redirect()->route('profesor.mesas')->with('mensaje','estas verificado');
        }

        return redirect()->back()->with('error','token incorrecto o no valido');
    }
}
