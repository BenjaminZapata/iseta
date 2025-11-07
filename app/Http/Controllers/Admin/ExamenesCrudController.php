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

    // 🧩 Validación 1: Solo se puede inscribir en mesas "por rendir" (estado = 0)
    if ($mesa->estado != 0) {
        return redirect()->back()->with('error', 'No se puede inscribir alumnos en una mesa que no está en estado "Por rendir".');
    }

    // 🧩 Validación 2: No se puede inscribir si faltan menos de 48 horas
    $fechaMesa = \Carbon\Carbon::parse($mesa->fecha);
    $ahora = now();

    if ($ahora->diffInHours($fechaMesa, false) < 48) {
        return redirect()->back()->with('error', 'No se puede inscribir: faltan menos de 48 horas para la mesa.');
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
        'asistencia'    => 0,
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
    // 🔹 Validación base
    $validated = $request->validate([
        'nota'        => 'nullable|numeric|min:0|max:10',
        'libro'       => 'nullable|integer|min:0|max_digits:4',
        'acta'        => 'nullable|integer|min:0|max_digits:4',
        'asistencia'  => 'required|in:0,1',
        'tipo_final'  => 'nullable|array',
        'tipo_final.*'=> 'integer|in:1,2,3,4',
    ], [
        'nota.numeric'       => 'La nota debe ser un número.',
        'nota.min'           => 'La nota mínima es 0.',
        'nota.max'           => 'La nota máxima es 10.',
        'libro.integer'      => 'El campo libro debe ser un número entero.',
        'libro.min'          => 'El número de libro no puede ser negativo.',
        'libro.max_digits'   => 'El número de libro no puede tener más de 4 dígitos.',
        'acta.integer'       => 'El campo acta debe ser un número entero.',
        'acta.min'           => 'El número de acta no puede ser negativo.',
        'acta.max_digits'    => 'El número de acta no puede tener más de 4 dígitos.',
        'asistencia.required'=> 'Debe indicar si el alumno estuvo presente o ausente.',
        'asistencia.in'      => 'El valor de asistencia no es válido.',
        'tipo_final.array'   => 'El tipo de final debe ser un array.',
        'tipo_final.*.in'    => 'Tipo de final no válido.',
    ]);

    // ⚠️ Solo se puede modificar ficha si mesa está rendida
    if ($examen->mesa->estado != 1) {
        return redirect()->back()->with('error', 'Solo se puede modificar la ficha cuando la mesa está en estado "Rendida".');
    }

    // ⚙️ Asistencia
    if ($request->asistencia == 0) {
        $examen->asistencia = 0;
        $examen->nota = null;
        $examen->aprobado = null;
    } else {
        if ($request->nota === null) {
            return redirect()->back()->with('error', 'Debe ingresar una nota si el alumno estuvo presente.');
        }
        $examen->asistencia = 1;
        $examen->nota = $request->nota;
        $examen->aprobado = $request->nota >= 4 ? 1 : 0;
    }

    // ⚙️ Tipo de final según estado de cursada
    $tipos = $request->input('tipo_final', []);

    if ($examen->alumno->estado_cursada === 'libre') {
        // Solo Escrito (1) y Oral (2) permitidos
        foreach ($tipos as $tipo) {
            if (!in_array($tipo, [1, 2])) {
                return redirect()->back()->with('error', 'Un alumno libre solo puede tener tipo de final Oral o Escrito.');
            }
        }
        $examen->tipo_final = implode(',', $tipos); // Guardar CSV
    } elseif ($examen->alumno->estado_cursada === 'promocion') {
        if (count($tipos) > 1) {
            return redirect()->back()->with('error', 'Un alumno en promoción solo puede tener un tipo de final.');
        }
        if ($request->nota < 7) {
            return redirect()->back()->with('error', 'Para estado Promoción la nota debe ser 7 o superior.');
        }
        $examen->tipo_final = $tipos[0] ?? null;
    } else {
        // Otros estados → solo un tipo permitido
        if (count($tipos) > 1) {
            return redirect()->back()->with('error', 'Solo se permite un tipo de final para este estado.');
        }
        $examen->tipo_final = $tipos[0] ?? null;
    }

    // ⚙️ Guardado de otros campos
    $examen->libro = $request->libro;
    $examen->acta = $request->acta;
    $examen->save();

    return redirect()->back()->with('mensaje', 'Se modificó el examen correctamente.');
}


    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    $examen = Examen::find($id);

    if (!$examen) {
        return redirect()->back()->with('error', 'La inscripción no existe.');
    }

    $mesa = Mesa::find($examen->id_mesa);
    if (!$mesa) {
        return redirect()->back()->with('error', 'La mesa asociada no existe.');
    }

    // 🧩 Validación: Solo puede eliminarse si faltan MÁS de 24 horas
    $fechaMesa = \Carbon\Carbon::parse($mesa->fecha);
    $ahora = now();

    if ($ahora->greaterThanOrEqualTo($fechaMesa->subHours(24))) {
        return redirect()->back()->with('error', 'No se puede eliminar la inscripción: faltan menos de 24 horas para la mesa o ya se realizó.');
    }

    $examen->delete();

    return redirect()->back()->with('mensaje', 'Inscripción eliminada correctamente.');
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
}
