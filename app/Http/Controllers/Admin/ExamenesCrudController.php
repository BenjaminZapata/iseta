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
    $TIPO_FINAL_MULTIPLE = 99; // Valor extremo para múltiples seleccionados
    $mesa = $examen->mesa;

    // --- Validación de estado de mesa ---
    if (!$mesa || $mesa->estado !== 1) {
        return redirect()
            ->route('admin.mesas.edit', ['mesa' => $mesa->id ?? $examen->id_mesa])
            ->with('error', 'La mesa aún no está rendida. Debe rendirse antes de modificar la ficha de examen.');
    }

    // -------------------------
    // Normalizar inputs antes de validar
    // -------------------------
    $normalized = $request->all();

    if ($request->has('asistencia')) {
        $normalized['asistencia'] = intval($request->input('asistencia')) ? '1' : '0';
    }

    if ($request->has('estado')) {
        $normalized['estado'] = intval($request->input('estado'));
    }

    if ($request->has('nota') && $request->input('nota') === '') {
        $normalized['nota'] = null;
    }

    $request->replace($normalized);

    // ============================================
    // VALIDACIONES
    // ============================================

    $rules = [
        'asistencia' => 'required|in:0,1',
        'nota'       => 'required_if:asistencia,1|integer|min:1|max:10',
        'libro'      => 'required|digits_between:1,4',
        'acta'       => 'required|digits_between:1,4',
        'estado'     => 'required|in:1,2,3',
    ];

    // Reglas para tipo_final
    if ($request->input('estado') == 2) {
        $rules['tipo_final'] = 'required'; // Libre, puede ser array
    } else {
        $rules['tipo_final'] = 'required|in:1,2,3';
    }

    $messages = [
        'asistencia.required' => 'Debe indicar si el alumno asistió o no.',
        'asistencia.in'       => 'El valor de asistencia no es válido.',
        'nota.required_if'    => 'Debe ingresar una nota porque el alumno asistió.',
        'nota.integer'        => 'La nota debe ser un número entero.',
        'nota.min'            => 'La nota mínima permitida es 1.',
        'nota.max'            => 'La nota máxima permitida es 10.',
        'libro.required'      => 'Debe ingresar el número de libro.',
        'libro.digits_between'=> 'El número de libro debe tener entre 1 y 4 dígitos.',
        'acta.required'       => 'Debe ingresar el número de acta.',
        'acta.digits_between' => 'El número de acta debe tener entre 1 y 4 dígitos.',
        'estado.required'     => 'Debe seleccionar el estado.',
        'estado.in'           => 'El estado seleccionado no es válido.',
        'tipo_final.required' => 'Debe seleccionar el tipo de final.',
    ];

    $validated = $request->validate($rules, $messages);

    // ============================================
    // LÓGICA DEL EXAMEN
    // ============================================

    $examen->asistencia = intval($validated['asistencia']);

    if ($examen->asistencia === 0) {
        $examen->nota = null;
        $examen->aprobado = null;
    } else {
        $nota = intval($validated['nota']);
        $examen->nota = $nota;

        if (($validated['tipo_final'] ?? 0) == 3 && $nota < 7) {
            return back()->with('error', 'Para Promoción, la nota debe ser mayor o igual a 7.')
                         ->withInput();
        }

        $examen->aprobado = $nota >= 4 ? 1 : 0;
    }

    // --------------------------
    // Tipo final con soporte múltiple
    // --------------------------
    $tipoFinal = $request->input('tipo_final');

    if ($examen->estado == 2) { // Libre
        if (is_array($tipoFinal) && count($tipoFinal) > 1) {
            $examen->tipo_final = $TIPO_FINAL_MULTIPLE; // asignar valor extremo
        } else {
            $examen->tipo_final = intval(is_array($tipoFinal) ? $tipoFinal[0] : $tipoFinal);
        }
    } else {
        $examen->tipo_final = intval(is_array($tipoFinal) ? $tipoFinal[0] : $tipoFinal);
    }

    $examen->estado = intval($validated['estado']);
    $examen->libro  = $validated['libro'];
    $examen->acta   = $validated['acta'];

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
