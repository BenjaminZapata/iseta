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
    function __construct(AdminRepository $repo)
{
    $this->middleware('auth:admin');
    $this->repo = $repo;
}
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $porPagina = Configuracion::get('filas_por_tabla', true);

    $username = $request->input('filtro'); // campo de búsqueda por nombre de usuario
    $rol = $request->input('rol');         // campo select en tu formulario

    $admins = $this->repo->getAdmins($username, $rol, $porPagina);

    return view('Admin.Admins.index', [
        'admins' => $admins,
        'filtro' => $username,
        'rolSeleccionado' => $rol,
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $roles = [
        'regente' => 0,
        'preceptor' => 1,
        'secretario' => 2
    ];

    // Validación amigable
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
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        'rol.required' => 'Debes seleccionar un rol.',
        'rol.in' => 'El rol seleccionado no es válido.',
    ]);

    $validated['password'] = bcrypt($validated['password']);
    $validated['rol'] = $roles[$validated['rol']];

    Admin::create($validated);

    return redirect()->back()->with('mensaje', 'Administrador creado correctamente');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Admin::findOrFail($id);

        $roles = [
            'regente' => 0,
            'preceptor' => 1,
            'secretario' => 2
        ];
        // Validación amigable
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:administradores,username,' . $admin->id,
            'password' => 'nullable|string|min:8|max:16|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$',
            'rol' => 'required|in:regente,preceptor,secretario',
            'email' => 'required|string|email|max:128|unique:administradores,email,' . $admin->id,
        ], [
            'username.required' => 'El campo usuario es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'rol.required' => 'Debes seleccionar un rol.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ]);
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        $validated['rol'] = $roles[$validated['rol']];
        $admin->update($validated);
        return redirect()->back()->with('mensaje', 'Administrador actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        if (Admin::count() <= 1) {
            return \redirect()->back()->with('error', 'Debe haber como minimo una cuenta de administrador');
        }
        $admin->delete();
        return redirect()->back()->with('mensaje', 'Se ha eliminado el administrador');
    }
}
