<div class="p-3">

    <link rel="stylesheet" href="{{ asset('css/Admin/cursadas.css') }}">

    {{-- 🔍 Buscar alumno --}}
    <div class="card shadow-sm mb-4 buscador-card">
        <div class="card-body">
            <fieldset class="border rounded p-3">
                <legend class="float-none w-auto px-2 text-muted fs-6">
                    <i class="bi bi-search"></i> Filtros de búsqueda
                </legend>
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" wire:model.live="nombre" class="form-control"
                            placeholder="Ej: Javier">
                    </div>
                    <div class="col-md-4">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" id="apellido" wire:model.live="apellido" class="form-control"
                            placeholder="Ej: Torres">
                    </div>
                    <div class="col-md-4">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" id="dni" wire:model.live="dni" class="form-control"
                            placeholder="Ej: 47260126">
                    </div>
                </div>
            </fieldset>
        </div>
    </div>

    {{-- 📋 Resultados de búsqueda con scroll --}}
    @if ($nombre || $apellido || $dni)
        <div class="card shadow-sm mb-4 resultados-card">
            <div class="card-header text-white fw-bold d-flex justify-content-between align-items-center">
                <span>Lista de alumnos</span>
                <span class="badge bg-light text-dark">{{ count($alumnos) }} {{ count($alumnos)==1 ? 'resultado' : 'resultados' }}</span>
            </div>
            <div class="tabla-scroll">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light sticky-top" style="background-color:#140b5c; color:white;">
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>DNI</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alumnos as $alumno)
                            <tr>
                                <td>{{ $alumno->nombre }}</td>
                                <td>{{ $alumno->apellido }}</td>
                                <td>{{ $alumno->dni }}</td>
                                <td class="text-center">
                                    <button wire:click="seleccionarAlumno({{ $alumno->id }})" class="btn btn-modificar">
                                        Seleccionar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No se encontraron resultados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- 🎓 Alumno seleccionado --}}
    @if ($alumnoSeleccionado)
        <div class="card shadow-sm mb-4 alumno-seleccionado">
            <div class="card-body">
                <div class="datos-alumno mb-3">
                    <strong>Nombre completo:</strong> {{ $alumnoSeleccionado->apellido }}, {{ $alumnoSeleccionado->nombre }}
                </div>

                <div class="bloque-carrera mb-3">
                    <label class="form-label">Carrera</label>
                    <select wire:model="carreraSeleccionada" wire:change="activarBoton" class="form-select">
                        <option value="">Seleccionar una carrera...</option>
                        @foreach ($alumnoSeleccionado->egresadoinscripto as $egresado)
                            <option value="{{ $egresado->id_carrera }}">{{ $egresado->carrera->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($mostrarBoton)
                    <div class="text-end">
                        <button wire:click="verMaterias" class="btn_blue" style="background-color:#140b5c;">
                            Ver asignaturas
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

{{-- 📚 Asignaturas disponibles (agrupadas por año desde carrera_asignatura_profesores) --}}
@if ($materiasCarrera && count($materiasCarrera))
    @php
        // Agrupamos por año obtenido desde la tabla pivote carrera_asignatura_profesores
       $materiasPorAnio = collect($materiasCarrera)->groupBy(function($m) {
    return isset($m->pivot) && isset($m->pivot->anio) ? $m->pivot->anio + 1 : 'Sin año';
});

    @endphp

    <div class="card shadow-sm mb-4">
        <div class="card-header text-white fw-bold" style="background-color: #140b5c;">
            <i class="bi bi-journal-bookmark-fill"></i> Asignaturas disponibles
        </div>

        <div class="card-body">
            <div class="accordion" id="accordionMaterias">
                @foreach ($materiasPorAnio as $anio => $lista)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{ $anio }}">
                            <button class="accordion-button collapsed fw-semibold"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $anio }}"
                                aria-expanded="false"
                                aria-controls="collapse-{{ $anio }}"
                                style="background-color: #f4f5f9; color: #140b5c;">
                                {{ is_numeric($anio) ? $anio . '° Año' : $anio }}
                            </button>
                        </h2>

                        <div id="collapse-{{ $anio }}" class="accordion-collapse collapse"
                            aria-labelledby="heading-{{ $anio }}"
                            data-bs-parent="#accordionMaterias">
                            <div class="accordion-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead style="background-color: #140b5c; color: white;">
                                            <tr>
                                                <th><i class="bi bi-book me-2"></i>Asignatura</th>
                                                <th class="text-center" style="width: 120px;">
                                                    <i class="bi bi-check-square me-2"></i>Seleccionar
                                                </th>
                                                <th style="width: 200px;">
                                                    <i class="bi bi-clipboard-check me-2 center"></i>Condición
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($lista as $asignatura)
<tr wire:key="asignatura-{{ $asignatura->id }}"
    @if(isset($asignaturasBloqueadas[$asignatura->id])) class="table-warning" @endif>
    <td class="fw-semibold">{{ $asignatura->nombre }}</td>
    <td class="text-center">
        <input type="checkbox"
            wire:model="asignaturasSeleccionadas"
            value="{{ (int) $asignatura->id }}"
            @if(isset($asignaturasBloqueadas[$asignatura->id])) disabled @endif
            title="{{ $asignaturasBloqueadas[$asignatura->id] ?? '' }}">
    </td>
    <td>
    <select wire:model="condiciones.{{ $asignatura->id }}"
        class="form-select form-select-sm"
        @if(isset($asignaturasBloqueadas[$asignatura->id])) disabled @endif>
        <option value="">Seleccionar condición...</option>
        <option value="1">Regular</option>
        <option value="0">Libre</option>
        <option value="2">Itinerante</option>
        <option value="3">Oyente</option>
    </select>

    @if(isset($asignaturasBloqueadas[$asignatura->id]))
        <div class="tooltip-correlativa">
            <strong>Correlativas faltantes:</strong> {{ $asignaturasBloqueadas[$asignatura->id] }}
        </div>
    @endif
</td>
</tr>
@endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

    {{-- 🔘 Botones --}}
    <div class="botones-derecha mt-3 d-flex justify-content-end gap-2">
        
        <x-btn-cancelar />
        @if ($materiasCarrera && count($materiasCarrera))
            <button wire:click="guardarCursada" class="btn_blue" style="background-color:#140b5c;">
                Guardar cursadas
            </button>
        @endif
    </div>
</div>
