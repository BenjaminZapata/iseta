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
            <div class="card-header text-white fw-bold d-flex justify-content-between align-items-center" style="background-color:#140b5c;">
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
            <div class="card-header text-white fw-bold" style="background-color:#140b5c;">
                Alumno seleccionado
            </div>
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

    {{-- 📚 Asignaturas con acordeón por año --}}
    @if ($materiasCarrera && count($materiasCarrera))
        @php
            $asignaturasPorAno = $materiasCarrera->groupBy('anio');
        @endphp
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white fw-bold" style="background-color:#140b5c;">
                Asignaturas disponibles
            </div>
            <div class="card-body">
                <div class="accordion" id="accordionMaterias">
                    @foreach ($asignaturasPorAno as $anio => $asignaturas)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $anio }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $anio }}" aria-expanded="false"
                                    aria-controls="collapse{{ $anio }}">
                                    {{ $anio }}° año
                                </button>
                            </h2>
                            <div id="collapse{{ $anio }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $anio }}" data-bs-parent="#accordionMaterias">
                                <div class="accordion-body p-0">
                                    <table class="table table-bordered table-hover mb-0 table-asignaturas">
                                        <thead class="table-light" style="background-color:#140b5c; color:white;">
                                            <tr>
                                                <th>Asignatura</th>
                                                <th class="text-center">Seleccionar</th>
                                                <th>Condición</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($asignaturas as $asignatura)
                                                <tr wire:key="asignatura-{{ $asignatura->id }}">
                                                    <td>{{ $asignatura->nombre }}</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" wire:model="asignaturasSeleccionadas" value="{{ $asignatura->id }}">
                                                    </td>
                                                    <td>
                                                        <select wire:model="condiciones.{{ $asignatura->id }}" class="form-select form-select-sm">
                                                            <option value="">Seleccionar...</option>
                                                            <option value="1">Regular</option>
                                                            <option value="0">Libre</option>
                                                            <option value="2">Itinerante</option>
                                                            <option value="3">Oyente</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
