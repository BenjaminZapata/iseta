<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Examen;
use App\Models\Mesa;
use App\Services\AlumnoInscripcionService;
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

        Examen::create([
            'id_alumno'     => $alumno->id,
            'id_mesa'       => $mesa->id,
            'id_asignatura' => $mesa->id_asignatura,
            'nota'          => null,
            'aprobado'      => null,
            'asistencia'    => 0, // Por defecto no asistió (hasta rendir)
            'fecha'         => now(),
        ]);

        return redirect()->back()->with('mensaje', 'Se ha inscrito al alumno correctamente.');
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
        $mesa = $examen->mesa;

      
        if ($mesa->estado !== 'rendida') {
            return back()->with('error', 'La mesa aún no está rendida. No se puede modificar la ficha de examen.');
        }

        $request->validate([
            'asistencia' => 'required|in:0,1',
            'estado'     => 'required|in:0,1,2', // Libre, Regular, Promoción
            'nota'       => 'nullable|numeric|min:1|max:10',
            'libro'      => 'required|digits_between:1,4',
            'acta'       => 'required|digits_between:1,4',
            'tipo_final' => 'required|in:1,2,3', // Escrito, Oral, Promocionado
        ]);

        $asistencia = intval($request->asistencia);
        $examen->asistencia = $asistencia;

        if ($asistencia === 0) {
            // AUSENTE ⇒ Nota debe quedar NULL
            $examen->nota = null;
            $examen->aprobado = null;
        }

        $estado = intval($request->estado);
        $examen->estado = $estado;

        
        if ($asistencia === 1) {

            
            if ($request->nota === null) {
                return back()->with('error', 'La nota es obligatoria cuando el alumno está presente.')
                             ->withInput();
            }

            $nota = intval($request->nota);
            $examen->nota = $nota;

            if ($estado === 2 && $nota < 7) {
                return back()->with('error', 'Para estado Promoción, la nota debe ser mayor o igual a 7.')
                             ->withInput();
            }

         
            $examen->aprobado = $nota >= 4 ? 1 : 0;
        }

        $tipo_final = intval($request->tipo_final);

        if ($estado === 0 && !in_array($tipo_final, [1, 2])) {
            return back()->with('error', 'Para estado Libre solo puede seleccionar Final Escrito u Oral.')
                         ->withInput();
        }

        $examen->tipo_final = $tipo_final;

        $examen->libro = $request->libro;
        $examen->acta  = $request->acta;
        $examen->save();

        return back()->with('mensaje', 'La ficha de examen fue modificada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Examen $examen)
    {
        $mesa = $examen->mesa;
        $examen->delete();

        if ($mesa) {
            return redirect()
                ->route('admin.mesas.edit', ['mesa' => $mesa->id])
                ->with('mensaje', 'Se ha eliminado el examen.');
        }

        return redirect()
            ->route('admin.mesas.index')
            ->with('mensaje', 'Se ha eliminado el examen.');
    }

    /**
     * Modificar nota rápidamente desde listado.
     */
    public function modificarNota(Request $request, Examen $examen)
    {
        if (!$request->has('nota')) {
            return redirect()->back()->with('error', 'Debes ingresar una nota.');
        }

        // 🔹 Si se marcó como ausente manualmente
        if ($request->input('nota') === 'a') {
            $examen->nota = 0;
            $examen->aprobado = null;
            $examen->asistencia = 0;
            $examen->save();

            return redirect()->back()->with('mensaje', 'Se ha marcado como ausente.');
        }

        $nota = $request->input('nota');

        if (!is_numeric($nota) || $nota < 0 || $nota > 10) {
            return redirect()->back()->with('error', 'La nota debe estar entre 0 y 10.');
        }

        $examen->nota = $nota;
        $examen->asistencia = 1;
        $examen->aprobado = $nota > 4 ? 1 : 0;
        $examen->save();

        return redirect()->back()->with('mensaje', 'Se ha actualizado la nota correctamente.');
    }

    /**
     * Borrar la inscripción del alumno de una mesa, si faltan más de 24hs.
     */
    public function BorrarInscripcionMesa(Examen $examen)
    {
        $fechaMesa = $examen->mesa->fecha;
        $ahora = now();

        // Verificar si faltan al menos 24 horas
        if ($ahora->diffInHours($fechaMesa, false) < 24) {
            return redirect()->back()->with('error', 'No se puede eliminar la inscripción: faltan menos de 24 horas para la mesa.');
        }

        $examen->delete();

        return redirect()->back()->with('mensaje', 'Inscripción eliminada correctamente.');
    }
}
