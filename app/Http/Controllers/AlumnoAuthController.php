<?php
namespace App\Http\Controllers;

use App\Http\Requests\AlumnoLoginRequest;
use App\Http\Requests\AlumnoRegistroRequest;
use App\Http\Requests\ModificarPasswordRequest;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 *
 * Autenticacion (login, registro, etc) de los alumnos
 * Utilizan el guard: alumno
 *
 */

class AlumnoAuthController extends Controller
{

    /**
     * Debes ser guest para acceder a las rutas
     * excepto para cerrar sesion
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout','cambiarPassword');
        $this->middleware('auth:web')->only(['logout','cambiarPassword']);
        $this->middleware('verificado')->only(['logout','cambiarPassword']);
    }

    /**
     * muestra la ruta de registro
     */
    public function registroView(){
        return view('Alumnos.Auth.registro');
    }

    /**
     * valida los datos de registro y actualiza su contraseña
     */
    public function registro(AlumnoRegistroRequest $request){
        $validated = $request->validated();

        $passwordGiven = $validated['password'];

        // si existe el correo y tiene password a 0
        $alumno = Alumno::existeSinPassword($validated);

        if(!$alumno )
            return redirect()->back()->withInput()->with('error','mail y dni no coinciden o ya esta registrado');

        // setea password
        $alumno -> password = bcrypt($passwordGiven);
        $alumno -> save();

        Auth::login($alumno);

        return redirect() -> route('token.enviar.mail');
    }

    /**
     * muestra vista de login
     */
    function loginView(): View{
        return view('Alumnos.Auth.login');
    }

    /**
     * valida datos y loguea al alumno
     */
    function login(AlumnoLoginRequest $request){
        $validated = $request->validated();

        $emailGiven = $validated['email'];
        $passwordGiven = $validated['password'];

        $alumno = Alumno::where('email',$emailGiven)->first();

        if(!$alumno || !Hash::check($passwordGiven, $alumno->password))
            return redirect()->route('alumno.login')->withInput()->with('error','Datos de usuario incorrectos');

        Auth::guard('admin')->logout();

        Auth::login($alumno);
        return redirect()->route('alumno.inscripciones')->with('mensaje', 'Has iniciado sesion correctamente');
    }

    /**
     * cierra sesion del alumno
     */
    function logout(){
        Auth::logout();
        return redirect()->route('alumno.login')->with('Has cerrado sesion');
    }



   private function cambiarPassword(ModificarPasswordRequest $request){
        /** @var \App\Models\Alumno $alumno **/
        $alumno = Auth::user();

        if(!Hash::check($request->oldPassword, $alumno->password)){
            return redirect()->back()->with('error','Las contraseña actual es incorrecta');
        }

        if($request->newPassword != $request->newPassword_confirmation){
            return redirect()->back()->with('error','Las contraseñas no coinciden');
        }

        $alumno->password = bcrypt($request->newPassword);
        $alumno->save();

        return redirect()->back()->with('mensaje','Se ha restablecido la contraseña');
    }
}
