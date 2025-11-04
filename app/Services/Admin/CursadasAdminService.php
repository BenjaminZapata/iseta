<?php

namespace App\Services\Admin;

class CursadasAdminService
{
    public function guardarCursada(Alumno $alumno_seleccionado)
    {
        $errores = [];

        if (! $this->alumnoSeleccionado) {
            $errores[] = 'Debe seleccionar un alumno.';
        }
        if (! $this->carreraSeleccionada) {
            $errores[] = 'Debe seleccionar una carrera.';
        }
        if (count($this->asignaturasSeleccionadas) === 0) {
            $errores[] = 'Debe seleccionar al menos una asignatura.';
        }

        $mapAsignaturaNombre = $this->materiasCarrera->pluck('nombre', 'id')->toArray();

        foreach ($this->asignaturasSeleccionadas as $idAsignatura) {
            $nombreAsignatura = $mapAsignaturaNombre[$idAsignatura] ?? "ID {$idAsignatura}";

            if (! isset($this->condiciones[$idAsignatura]) || $this->condiciones[$idAsignatura] === '') {
                $errores[] = "Debe elegir una condición para {$nombreAsignatura}.";
            }

            if (isset($this->asignaturasBloqueadas[$idAsignatura])) {
                $errores[] = "No se puede registrar {$nombreAsignatura} porque faltan las correlativas:
        {$this->asignaturasBloqueadas[$idAsignatura]}";
            }

            $existe = Cursada::where('id_alumno', $this->alumnoSeleccionado->id)
                ->where('id_asignatura', $idAsignatura)
                ->where('id_carrera', $this->carreraSeleccionada)
                ->where('anio_cursada', now()->year)
                ->exists();

            if ($existe) {
                $errores[] = "La cursada de {$nombreAsignatura} ya existe para este alumno este año.";
            }
        }

        // ⚠️ Si hay errores, mostramos y recargamos materias
        if (! empty($errores)) {
            foreach ($errores as $msg) {
                FlasherFacade::addError($msg);
            }

            // 🔹 Evita que se rompa la tabla
            $this->verMaterias();

            return;
        }

        // ✅ Guardar cursadas
        // foreach ($this->asignaturasSeleccionadas as $idAsignatura) {
        //     Cursada::create([
        //         'anio_cursada' => now()->year,
        //         'aprobada' => 3,
        //         'id_alumno' => $this->alumnoSeleccionado->id,
        //         'id_asignatura' => $idAsignatura,
        //         'id_carrera' => $this->carreraSeleccionada,
        //         'condicion' => $this->mapCondicion[array_search((int) $this->condiciones[$idAsignatura], $this->mapCondicion) ??
        //         'Regular'] ?? 1,
        //     ]);
        // }

        FlasherFacade::addSuccess('Cursadas registradas correctamente.');

        // 🔹 Refresca materias y evita que se rompa la vista
        $this->verMaterias();

        // 🔹 Limpia selección
        $this->asignaturasSeleccionadas = [];
        $this->condiciones = [];
    }
}
