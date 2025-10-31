<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Repositories\Admin\AdminRepository;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Validator;
use App\Mail\Admins;
use Illuminate\Support\Facades\Mail;
use Log;

class AdminsCrudController extends Controller
{
    protected $repo;

    public function __construct(AdminRepository $repo)
    {
        $this->middleware('auth:admin');
        $this->repo = $repo;
    }

    /**
     * Mostrar listado de administradores
     */
    public function index(Request $request)
    {
        $porPagina = Configuracion::get('filas_por_tabla', true);

        $username = $request->input('filtro'); // filtro por username
        $rol = $request->input('rol');         // filtro por rol

        $admins = $this->repo->getAdmins($username, $rol, $porPagina);

        $filters = (object) [
            'username' => $username,
            'rol' => $rol,
        ];

        return view('Admin.Admins.index', [
            'admins' => $admins,
            'filters' => $filters,
        ]);
    }

    /**
     * Crear nuevo administrador
     */
    public function store(Request $request)
    {
        $roles = ['regente' => 0, 'preceptor' => 1, 'secretario' => 2];

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:administradores,username',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:16',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/'
            ],
            'rol' => 'required|in:regente,preceptor,secretario',
            'email' => 'required|string|email|max:128|unique:administradores,email',
        ], [
            'username.required' => 'El campo usuario es obligatorio.',
            'username.unique' => 'El nombre del usuario ya está en uso.',
            'email.required' => 'El campo email es obligatorio.',
            'email.unique' => 'El email ya está en uso.',
            'username.max' => 'El campo usuario no debe contener mas de 50 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.regex' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'rol.required' => 'Debes seleccionar un rol.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ], [
            'username' => 'Usuario'
        ]);

        try {
            // Enviar mail con las credenciales o aviso
            Mail::to($validated['email'])->queue(new Admins($validated));
        } catch (\Throwable $th) {
            Log::error('Error al enviar mail de creación de usuario: ' . $th->getMessage());
        }

        $validated['password'] = bcrypt($validated['password']);
        $validated['rol'] = $roles[$validated['rol']];

        Admin::create($validated);

        return redirect()->back()->with('mensaje', 'Administrador creado correctamente');
    }

    /**
     * Actualizar administrador (para edición inline)
     */
public function update(Request $request, Admin $admin)
{
    $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:administradores,username',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:16',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/'
            ],
            'rol' => 'required|in:regente,preceptor,secretario',
            'email' => 'required|string|email|max:128|unique:administradores,email',
        ], [
            'username.required' => 'El campo usuario es obligatorio.',
            'username.unique' => 'El nombre del usuario ya está en uso.',
            'email.required' => 'El campo email es obligatorio.',
            'email.unique' => 'El email ya está en uso.',
            'username.max' => 'El campo usuario no debe contener mas de 50 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.regex' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'rol.required' => 'Debes seleccionar un rol.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ], [
            'username' => 'Usuario'
        ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()]);
    }
     try {
    // Enviar mail de modificación de admin
    $data = $validator->validated();
    Mail::to($data['email'])->queue(new Admins($data, 'modificado'));
} catch (\Throwable $th) {
    Log::error('Error al enviar mail de modificación de admin: ' . $th->getMessage());
}

    $data = $validator->validated();

    $admin->username = $data['username'];
    $admin->email = $data['email'];
    $admin->rol = $data['rol'];

    if (!empty($data['password'])) {
        $admin->password = bcrypt($data['password']);
    }

    $admin->save();

    return response()->json(['success' => true, 'message' => 'Administrador modificado correctamente']);
}


    /**
     * Eliminar administrador
     */
 public function destroy(Admin $admin)
{
    if (Admin::count() <= 1) {
        return redirect()->back()->with('error', 'Debe haber como mínimo una cuenta de administrador');
    }

    if ($admin->id === auth()->id()) {
        return redirect()->back()->with('error', 'No podés eliminar tu propia cuenta mientras estás logueado');
    }

    $admin->delete();

    return redirect()->back()->with('mensaje', 'Se ha eliminado el administrador');
}
  public function eliminarMasivo(Request $request)
{
    $ids = explode(',', $request->ids);
    $usuarioActualId = auth()->id();

    // Filtrar el ID del usuario actual
    $idsFiltrados = array_filter($ids, fn($id) => (int)$id !== $usuarioActualId);

    if (empty($idsFiltrados)) {
        return redirect()->back()->with('error', 'No se puede eliminar tu propia cuenta');
    }

    Admin::whereIn('id', $idsFiltrados)->delete();

    return redirect()->back()->with('success', 'Usuarios eliminados correctamente.');
}

}
