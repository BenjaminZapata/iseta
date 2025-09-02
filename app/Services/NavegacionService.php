<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

class NavegacionService
{
 /**
  * Devuelve un array de items ['label' => ..., 'url' => ...] en orden jerárquico.
  */
 public static function detectarOrigen(): array
 {
  $route = Route::current();
  $name = $route?->getName();
  $crumbs = [];

  if (!$name) {
   return [];
  }

  // Ocultar breadcrumbs en las vistas index
  if (str_ends_with($name, '.index')) {
   return [];
  }

  // -----------------------
  // ALUMNOS
  // -----------------------
  if (str_starts_with($name, 'admin.alumnos')) {
   $crumbs[] = ['label' => 'Alumnos', 'url' => route('admin.alumnos.index')];

   if ($name === 'admin.alumnos.edit') {
    $alumno = request()->route('alumno');
    if ($alumno) {
     $crumbs[] = [
      'label' => $alumno->apellido . ', ' . $alumno->nombre,
      'url'   => null
     ];
    }
   }
  }

  // -----------------------
  // PROFESORES
  // -----------------------
  if (str_starts_with($name, 'admin.profesores')) {
   $crumbs[] = ['label' => 'Profesores', 'url' => route('admin.profesores.index')];

   if ($name === 'admin.profesores.edit') {
    $profesor = request()->route('profesor');
    if ($profesor) {
     $crumbs[] = [
      'label' => $profesor->apellido . ', ' . $profesor->nombre,
      'url'   => null
     ];
    }
   }
  }

  // -----------------------
  // CARRERAS
  // -----------------------
  if (str_starts_with($name, 'admin.carreras')) {
   $crumbs[] = ['label' => 'Carreras', 'url' => route('admin.carreras.index')];

   if ($name === 'admin.carreras.edit') {
    $carrera = request()->route('carrera');
    if ($carrera) {
     $crumbs[] = ['label' => $carrera->nombre, 'url' => null];
    }
   }

   if ($name === 'admin.carreras.add_asignatura') {
    $carrera = request()->route('carrera');
    if ($carrera) {
     $crumbs[] = [
      'label' => $carrera->nombre,
      'url'   => route('admin.carreras.edit', $carrera)
     ];
     $crumbs[] = ['label' => 'Agregar asignatura', 'url' => null];
    }
   }

   if ($name === 'admin.carreras.create_asignatura') {
    $carrera = request()->route('carrera');
    if ($carrera) {
     $crumbs[] = [
      'label' => $carrera->nombre,
      'url'   => route('admin.carreras.edit', $carrera)
     ];
     $crumbs[] = ['label' => 'Crear asignatura', 'url' => null];
    }
   }
  }

  // -----------------------
  // ASIGNATURAS
  // -----------------------
  if (str_starts_with($name, 'admin.asignaturas')) {
   $crumbs[] = ['label' => 'Asignaturas', 'url' => route('admin.asignaturas.index')];

   if ($name === 'admin.asignaturas.edit') {
    $asignatura = request()->route('asignaturas');
    if ($asignatura) {
     $crumbs[] = ['label' => $asignatura->nombre, 'url' => null];
    }
   }
  }

  // -----------------------
  // MESAS
  // -----------------------
  if (str_starts_with($name, 'admin.mesas')) {
   $crumbs[] = ['label' => 'Mesas', 'url' => route('admin.mesas.index')];

   if ($name === 'admin.mesas.edit') {
    $mesa = request()->route('mesas');
    if ($mesa) {
     $crumbs[] = ['label' => 'Mesa ' . $mesa->id, 'url' => null];
    }
   }

   if ($name === 'admin.mesas.create_dual') {
    $carrera = request()->route('carrera');
    if ($carrera) {
     $crumbs[] = [
      'label' => $carrera->nombre,
      'url'   => route('admin.carreras.edit', $carrera)
     ];
    }
    $crumbs[] = ['label' => 'Crear mesa dual', 'url' => null];
   }
  }

  // -----------------------
  // CURSADAS
  // -----------------------
  if (str_starts_with($name, 'admin.cursadas')) {
   $crumbs[] = ['label' => 'Cursadas', 'url' => route('admin.cursadas.index')];

   if ($name === 'admin.cursadas.edit') {
    $cursada = request()->route('cursada');
    if ($cursada && $cursada->asignatura) {
     $crumbs[] = ['label' => $cursada->asignatura->nombre, 'url' => null];
    } else {
     $crumbs[] = ['label' => 'Cursada #' . $cursada->id, 'url' => null];
    }
   }
  }

  // -----------------------
  // INSCRIPTOS
  // -----------------------
  if (str_starts_with($name, 'admin.inscriptos')) {
   $crumbs[] = ['label' => 'inscriptos', 'url' => route('admin.inscriptos.index')];

   if ($name === 'admin.inscriptos.update') {
    $inscriptos = request()->route('insriptos');
    if ($inscriptos && $inscriptos->alumnos) {
     $crumbs[] = ['label' => $inscriptos->alumnos->nombre, 'url' => null];
    } else {
     $crumbs[] = ['label' => 'Inscriptos #' . $inscriptos->id, 'url' => null];
    }
   }
  }

  // -----------------------
  // EXÁMENES
  // -----------------------
  if (str_starts_with($name, 'admin.examenes')) {
   $crumbs[] = ['label' => 'Exámenes', 'url' => route('admin.examenes.index')];

   if ($name === 'admin.examenes.edit') {
    $examen = request()->route('examen');
    if ($examen) {
     if ($examen->cursada && $examen->cursada->alumno) {
      $alumno = $examen->cursada->alumno;
      $crumbs[] = [
       'label' => $alumno->apellido . ', ' . $alumno->nombre,
       'url'   => route('admin.alumnos.edit', $alumno)
      ];
     }
     $crumbs[] = [
      'label' => 'Examen #' . $examen->id,
      'url'   => null
     ];
    }
   }
  }

  return $crumbs;
 }
}
