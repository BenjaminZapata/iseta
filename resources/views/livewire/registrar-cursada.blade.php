<div class="p-3">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=add_circle" />

    <link rel="stylesheet" href="{{ asset('css/Admin/Cursadas/add-cursadas.css') }}">

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
                            <input type="text" id="dni" wire:model.live="dni" class="form-control"
                                placeholder="Ej: 47260126">
                        </div>

                        <div class="col-md-4">
                            <label for="nombre" class="form-label">Nombre y Apellido</label>
                            <input type="text" id="nombre" wire:model.live="nombre_apellido" class="form-control"
                                placeholder="Ej: Javier Torres o Torres">
                            @error('nombre')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- <div class="col-md-4">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" id="apellido" wire:model.live="apellido" class="form-control" placeholder="Ej: Torres">
                            @error('apellido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" id="dni" wire:model.live="dni" class="form-control" placeholder="Ej: 47260126">
                        @error('dni') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div> --}}
                    </div>
                </fieldset>
            </div>
        </div>

        {{-- 📋 Resultados de búsqueda --}}
        @if (!empty($dni) || !empty($nombre_apellido))
            <div class="card shadow-sm mb-4 resultados-card">
                <div class="card-header text-white fw-bold d-flex justify-content-between align-items-center">
                    <span class="mini-encabezado">Lista de alumnos</span>
                    <span class="badge bg-light text-dark">{{ count($alumnos) }}
                        {{ count($alumnos) == 1 ? 'resultado' : 'resultados' }}</span>
                </div>
                <div class="tabla-scroll">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light sticky-top" style="background-color:#140b5c; color:white;">
                            <tr>
                                <th class="center">Apellido</th>
                                <th class="center"> Nombre </th>
                                <th class="center">DNI</th>
                                <th class="center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($alumnos as $alumno)
                                <tr>
                                    <td class="bold">
                                        <div class="centrar">{{ $alumno->apellido }}</div>
                                    </td>
                                    <td class="bold">
                                        <div class="centrar">{{ $alumno->nombre }}</div>
                                    </td>
                                    <td class="bold">
                                        <div class="centrar">{{ $alumno->dni }}</div>
                                    </td>
                                    <td class="center">
                                        <div class="centrar">
                                            <button type="button" wire:click="seleccionarAlumno({{ $alumno->id }})"
                                                class="btn btn-modificar">
                                                Seleccionar
                                            </button>
                                        </div>

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

                        <div class="bloque-carrera mb-3">
                            <label class="form-label">Carrera</label>
                            <select wire:model="carreraSeleccionada" wire:change="verMaterias" class="form-select">
                                <option value="">Seleccionar una carrera...</option>
                                @foreach ($alumnoSeleccionado->egresadoinscripto as $egresado)
                                    <option value="{{ $egresado->id_carrera }}">{{ $egresado->carrera->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('carreraSeleccionada')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif

            {{-- 📚 Asignaturas disponibles (agrupadas por año) --}}
            @if ($materiasCarrera && count($materiasCarrera))
                @php
                    $materiasPorAnio = collect($materiasCarrera)->groupBy(function ($m) {
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
                                        <button class="accordion-button collapsed fw-semibold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse-{{ $anio }}"
                                            aria-expanded="false" aria-controls="collapse-{{ $anio }}"
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
                                                            <th class="center">Estado</th>
                                                            <th class="center" style="min-width: 150px;">Condición</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($lista as $asignatura)
                                                            <tr wire:key="asignatura-{{ $asignatura->id }}"
                                                                x-data="{ checked: false }" x-init="checked = @js(in_array($asignatura->id, $asignaturasSeleccionadas));"
                                                                x-on:click="$el.querySelector('input[type=checkbox]').click()"
                                                                x-bind:class="checked ? 'table-selected' : ''"
                                                                style="cursor: pointer; transition: background-color 0.2s;"
                                                                @if (isset($asignaturasBloqueadas[$asignatura->id])) class="table-warning-custom" @endif>
                                                                <td class="bold">
                                                                    @if (isset($asignaturasBloqueadas[$asignatura->id]))
                                                                        <div class="grid-correlativa">
                                                                            <div class="tooltip-correlativa">
                                                                                <i class="ti ti-alert-triangle"></i>
                                                                            </div>
                                                                            <div>
                                                                                {{ $asignatura->nombre }}
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <i x-show="checked"
                                                                            class="ti ti-circle-filled"
                                                                            style="color: #140b5c; margin-left: 8px;"></i>
                                                                        <i x-show="!checked" class="ti ti-circle"
                                                                            style="color: #140b5c; margin-left: 8px;"></i>
                                                                        <span
                                                                            style="margin-left: 8px;">{{ $asignatura->nombre }}</span>

                                                                        <!-- Checkbox sigue funcionando pero está completamente oculto -->
                                                                        <input type="checkbox" x-model="checked"
                                                                            wire:model="asignaturasSeleccionadas"
                                                                            value="{{ (int) $asignatura->id }}"
                                                                            class="hidden-checkbox"
                                                                            @if (isset($asignaturasBloqueadas[$asignatura->id])) disabled @endif>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if (isset($asignaturasBloqueadas[$asignatura->id]))
                                                                        <div class="tooltip-correlativa mt-1">
                                                                            <div class="centrar2">
                                                                                <strong>Correlativas faltantes:</strong>
                                                                                @foreach ($asignaturasBloqueadas[$asignatura->id] as $correlativa)
                                                                                    <div class="centrar">
                                                                                        <i class="ti ti-circle-filled"
                                                                                            style="font-size: 0.5em; margin-left: 12px;"></i>
                                                                                        <span
                                                                                            style="margin-left: 5px;">{{ $correlativa }}</span>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </td>

                                                                <td class="px-4 py-2 align-middle"
                                                                    style="min-width: 250px;">
                                                                    <div class="space-y-1 centrar" x-show="checked"
                                                                        x-transition.opacity.duration.150ms>
                                                                        <select @click.stop
                                                                            id="condicion_{{ $asignatura->id }}"
                                                                            wire:model="condiciones.{{ $asignatura->id }}"
                                                                            class="mt-1 block w-auto rounded-md border-gray-300 campo_info rounded shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm
                        @if (isset($asignaturasBloqueadas[$asignatura->id])) bg-gray-100 cursor-not-allowed @endif"
                                                                            @if (isset($asignaturasBloqueadas[$asignatura->id])) disabled @endif>
                                                                            <option value="">Seleccionar
                                                                                condición...
                                                                            </option>
                                                                            <option value="1">Regular</option>
                                                                            <option value="0">Libre</option>
                                                                            <option value="2">Itinerante</option>
                                                                            <option value="3">Oyente</option>
                                                                        </select>

                                                                        @if ($errors->has('condiciones.' . $asignatura->id))
                                                                            <span class="text-red-500 text-xs">
                                                                                {{ $errors->first('condiciones.' . $asignatura->id) }}
                                                                            </span>
                                                                        @endif
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
        @endif

        {{-- 🔘 Botones inferiores --}}
        <div class="botones-derecha mt-3 d-flex justify-content-end gap-2">
            <x-btn-cancelar :url="route('admin.cursadas.index')" />
            @if ($materiasCarrera && count($materiasCarrera))
                <button type="submit" class="btn_blue">
                    <i class="ti ti-device-floppy iconos"></i> Guardar cursadas
                </button>
            @endif
        </div>
    </form>
</div>
