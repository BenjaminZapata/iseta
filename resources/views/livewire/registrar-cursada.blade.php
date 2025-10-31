<div class="p-3">

    {{-- CSS personalizado --}}
    <link rel="stylesheet" href="{{ asset('css/Admin/cursadas.css') }}">

    {{-- Formulario de selección y guardado --}}
    <form wire:submit.prevent="guardarCursada" class="space-y-4">

        {{-- 🔍 Buscar alumno --}}
        <div class="card shadow-sm mb-4 buscador-card">
            <div class="card-body">
                <fieldset class="border rounded p-3">
                    <legend class="float-none w-auto px-2 text-muted fs-6">
                        <i class="bi bi-search"></i> Filtros de búsqueda
                    </legend>

                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" id="dni" wire:model.live="dni" class="form-control" placeholder="Ej: 47260126">
                        </div>

                        <div class="col-md-4">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" wire:model.live="nombre" class="form-control" placeholder="Ej: Javier">
                        </div>

                        <div class="col-md-4">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" id="apellido" wire:model.live="apellido" class="form-control" placeholder="Ej: Torres">
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        {{-- 📋 Resultados de búsqueda --}}
        @if ($nombre || $apellido || $dni)
        <div class="card shadow-sm mb-4 resultados-card">
            <div class="card-header text-white fw-bold d-flex justify-content-between align-items-center">
                <span class="mini-encabezado">Lista de alumnos</span>
                <span class="badge bg-light text-dark mini-encabezado">
                    {{ count($alumnos) }} {{ count($alumnos)==1 ? 'resultado' : 'resultados' }}
                </span>
            </div>

            <div class="tabla-scroll">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead>
                        <tr class="tabla-container">
                            <th class="center">Nombre</th>
                            <th class="center">Apellido</th>
                            <th class="center">DNI</th>
                            <th class="center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alumnos as $alumno)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; justify-content: center;">{{ $alumno->nombre }}</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; justify-content: center;">{{ $alumno->apellido }}</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; justify-content: center;">{{ $alumno->dni }}</div>
                            </td>
                            <td class="text-center">
                                <div style="display: flex; align-items: center; justify-content: center;">
                                    <button type="button" wire:click="seleccionarAlumno({{ $alumno->id }})" class="btn-modificar">
                                        <i class="bi bi-check-circle"></i> Seleccionar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                No se encontraron resultados
                            </td>
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
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-person-lines-fill"></i> Alumno seleccionado
                    </label>
                    <div class="nombre-alumno">
                        {{ $alumnoSeleccionado->apellido }}, {{ $alumnoSeleccionado->nombre }}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-mortarboard"></i> Carrera
                    </label>
                    <select wire:model="carreraSeleccionada" wire:change="activarBoton" class="form-select">
                        <option value="">Seleccionar una carrera...</option>
                        @foreach ($alumnoSeleccionado->egresadoinscripto as $egresado)
                        <option value="{{ $egresado->id_carrera }}">
                            {{ $egresado->carrera->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                @if ($mostrarBoton)
                <div class="text-end">
                    <button type="button" wire:click="verMaterias" class="btn_blue">
                        <i class="bi bi-book"></i> Ver asignaturas
                    </button>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- 📚 Asignaturas disponibles (agrupadas por año) --}}
        @if ($materiasCarrera && count($materiasCarrera))
        @php
        $materiasPorAnio = collect($materiasCarrera)->groupBy(function($m) {
        return isset($m->pivot) && isset($m->pivot->anio) ? $m->pivot->anio + 1 : 'Sin año';
        });
        @endphp

        <div class="card shadow-sm mb-4">
            <div class="card-header text-white fw-bold">
                <i class="bi bi-journal-bookmark-fill"></i>
                <span class="mini-encabezado"> Asignaturas disponibles</span>
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
                                                <th>Asignatura</th>
                                                <th class="centrar">Estado</th>
                                                <th>
                                                    <div class="centrar">Seleccionar</div>
                                                </th>
                                                <th>
                                                    <div class="centrar">Condición</div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lista as $asignatura)
                                            <tr wire:key="asignatura-{{ $asignatura->id }}"
                                                @if(isset($asignaturasBloqueadas[$asignatura->id])) class="table-warning-custom" @endif>

                                                {{-- NOMBRE ASIGNATURA --}}
                                                <td class="fw-semibold">
                                                    @if(isset($asignaturasBloqueadas[$asignatura->id]))
                                                    <div class="grid-correlativa">
                                                        <div class="tooltip-correlativa">
                                                            <i class="ti ti-alert-triangle"></i>
                                                        </div>
                                                        <div>{{ $asignatura->nombre }}</div>
                                                    </div>
                                                    @else
                                                    {{ $asignatura->nombre }}
                                                    @endif
                                                </td>

                                                {{-- ESTADO --}}
                                                <td>
                                                    <div class="centrar">
                                                        @if(isset($asignaturasBloqueadas[$asignatura->id]))

                                                        <div>
                                                            <strong>Correlativas faltantes:</strong>
                                                            {{ $asignaturasBloqueadas[$asignatura->id] }}
                                                        </div>

                                                        @endif
                                                    </div>
                                                </td>

                                                {{-- CHECKBOXS --}}

                                                <td>
                                                    <div class="centrar">
                                                        <input type="checkbox"
                                                            wire:model="asignaturasSeleccionadas"
                                                            value="{{ (int) $asignatura->id }}"
                                                            @if(isset($asignaturasBloqueadas[$asignatura->id])) disabled @endif
                                                        title="{{ $asignaturasBloqueadas[$asignatura->id] ?? '' }}">
                                                    </div>
                                                </td>

                                                {{-- CONDICION DE CURSADA --}}

                                                <td>
                                                    <div class="centrar">
                                                        <select wire:model="condiciones.{{ $asignatura->id }}"
                                                            class="form-select form-select-sm"
                                                            @if(isset($asignaturasBloqueadas[$asignatura->id])) disabled @endif>
                                                            <option value="">Seleccionar condición...</option>
                                                            <option value="1">Regular</option>
                                                            <option value="0">Libre</option>
                                                            <option value="2">Itinerante</option>
                                                            <option value="3">Oyente</option>
                                                        </select>
                                                    </div>
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

        {{-- 🔘 Botones inferiores --}}
        <div class="botones-derecha mt-3 d-flex justify-content-end gap-2">
            <x-btn-cancelar />
            @if ($materiasCarrera && count($materiasCarrera))
            <button type="submit" class="btn_blue">
                <i class="ti ti-device-floppy"></i> Guardar cursadas
            </button>
            @endif
        </div>
    </form>
</div>