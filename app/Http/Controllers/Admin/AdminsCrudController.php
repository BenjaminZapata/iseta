<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Repositories\Admin\AdminRepository;
use App\Models\Configuracion;
use Illuminate\Http\Request;

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
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'rol.required' => 'Debes seleccionar un rol.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ]);

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
    $data = $request->validate([
        'username' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'rol' => 'required|in:0,1,2',
        'password' => 'nullable|string|min:4',
    ]);

    $admin->username = $data['username'];
    $admin->email = $data['email'];
    $admin->rol = $data['rol'];

    if (!empty($data['password'])) {
        $admin->password = bcrypt($data['password']);
    }

    $admin->save();

    return response()->json(['success' => true]);
}

    /**
     * Eliminar administrador
     */
    public function destroy(Admin $admin)
    {
        if (Admin::count() <= 1) {
            return redirect()->back()->with('error', 'Debe haber como mínimo una cuenta de administrador');
        }

        $admin->delete();

        return redirect()->back()->with('mensaje', 'Se ha eliminado el administrador');
    }
}
