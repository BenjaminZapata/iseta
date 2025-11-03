<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Examen;
use App\Models\Mesa;
use App\Services\AlumnoInscripcionService;
use App\Services\DiasHabiles;
use Illuminate\Http\Request;

class ExamenesCrudController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, AlumnoInscripcionService $inscripcionService)
{
    if (!$request->has('id_alumno')) {
        return redirect()->back()->with('error', 'No has seleccionado ningún alumno.');
    }

    $mesa = Mesa::find($request->input('id_mesa'));
    if (!$mesa) {
        return redirect()->back()->with('error', 'La mesa seleccionada no existe.');
    }

    $alumno = Alumno::find($request->input('id_alumno'));
    if (!$alumno) {
        return redirect()->back()->with('error', 'El alumno seleccionado no existe o no se pudo cargar correctamente.');
    }

    // <-- Aquí opcionalmente podés comentar la verificación de inscripción
    // $comprobacion = $inscripcionService->puedeInscribirse($mesa, $alumno);
    // if (!$comprobacion['success']) {
    //     return redirect()->back()->with('error', $comprobacion['mensaje']);
    // }

    Examen::create([
        'id_alumno' => $alumno->id,
        'id_mesa' => $mesa->id,
        'id_asignatura' => $mesa->id_asignatura,
        'nota' => 0,
        'aprobado' => 0,
        'fecha' => now()
    ]);

    return redirect()->back()->with('mensaje', 'Se ha inscrito al alumno aunque ya haya rendido');
}




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Examen $examen)
    {
        return view('Admin.Examenes.edit', compact('examen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Examen $examen)
    {
        $request->validate([
            'nota' => 'nullable|numeric|min:0|max:10',
            'libro' => 'nullable|integer|max:100|min:0',
            'acta'=> 'nullable|integer|max:100|min:0',
            'ausente' => 'nullable',
            'tipo_final' => 'nullable | integer | between:0,4'
        ]);

        if ($request->ausente) {
            $examen->nota = 0;
            $examen->aprobado = 3;
        } elseif ($request->nota > 4) {
            $examen->nota = $request->nota;
            $examen->aprobado = 1;
        } else {
            $examen->nota = $request->nota;
            $examen->aprobado = 2;
        }
        $examen->tipo_final = $request->tipo_final;
        $examen->libro = $request->libro;
        $examen->acta = $request->acta;
        $examen->save();


        if ($request->has('redirect'))
            return redirect()->to($request->input('redirect'))->with('mensaje', 'Se modificó el examen');
        else
            return redirect()->back()->with('mensaje', 'Se modificó el examen');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Examen $examen)
    {
        $mesa = Mesa::where('id', $examen->id_mesa)->first();
        $examen->delete();

        if (!$mesa)
            return redirect()->route('admin.mesas.edit', ['mesa' => $mesa->id])->with('mensaje', 'Se ha eliminado el examen');
        else
            return redirect()->route('admin.mesas.index')->with('mensaje', 'Se ha eliminado el examen');
    }

    function modificarNota(Request $request, Examen $examen)
    {
        if (!$request->has('nota')) {
            return \redirect()->back()->with('Ingresa una nota');
        }

        if ($request->input('nota') == 'a') {
            $examen->aprobado = 3;
            $examen->save();
            return \redirect()->back()->with('Se ha actualizado la nota');
        }

        $nota = $request->input('nota');

        if (!is_numeric($nota) || $nota < 0 || $nota > 10) {
            return redirect()->back()->with('error', 'La nota debe estar entre 0 y 10');
        }

        $examen->nota = $request->input('nota');
        $examen->aprobado = $request->input('nota') >= 4 ? 1 : 2;
        $examen->save();
        return \redirect()->back()->with('mensaje', 'Se ha actualizado la nota');
    }

   public function BorrarInscripcionMesa(Examen $examen)
{
    $fechaMesa = $examen->mesa->fecha; // asumimos que es un campo tipo datetime
    $ahora = now();

    // Verificar si faltan al menos 24 horas
    if ($ahora->diffInHours($fechaMesa, false) < 24) {
        return redirect()->back()->with('error', 'No se puede eliminar la inscripción: faltan menos de 24 horas para la mesa.');
    }

    $examen->delete();

    return redirect()->back()->with('mensaje', 'Inscripción eliminada correctamente.');
}

}