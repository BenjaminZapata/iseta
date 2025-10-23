<div class="p-3">

    <link rel="stylesheet" href="{{ asset('css/Admin/cursada.css') }}">

    {{-- 🔍 Buscar alumno --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <fieldset class="border rounded p-3">
                <legend class="float-none w-auto px-2 text-muted fs-6">
                    <i class="bi bi-search"></i> Filtros de búsqueda
                </legend>
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="nombre" class="form-label"><i class="bi bi-person"></i> Nombre</label>
                        <input type="text" id="nombre" wire:model.live="nombre" class="form-control" placeholder="Ej: Javier">
                    </div>
                    <div class="col-md-4">
                        <label for="apellido" class="form-label"><i class="bi bi-person-badge"></i> Apellido</label>
                        <input type="text" id="apellido" wire:model.live="apellido" class="form-control" placeholder="Ej: Torres">
                    </div>
                    <div class="col-md-4">
                        <label for="dni" class="form-label"><i class="bi bi-credit-card"></i> DNI</label>
                        <input type="text" id="dni" wire:model.live="dni" class="form-control" placeholder="Ej: 47260126">
                    </div>
                </div>
            </fieldset>
        </div>
    </div>

    {{-- 📋 Resultados de búsqueda --}}
    @if ($nombre || $apellido || $dni)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-ul"></i> Lista de alumnos</span>
                <span class="badge bg-light text-dark">{{ count($alumnos) }} {{ count($alumnos) == 1 ? 'resultado' : 'resultados' }}</span>
            </div>
            <div class="tabla-scroll">
                <table class="table table-sm table-bordered table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-person-fill me-2"></i>Nombre</th>
                            <th><i class="bi bi-person-fill me-2"></i>Apellido</th>
                            <th><i class="bi bi-credit-card me-2"></i>DNI</th>
                            <th class="text-center"><i class="bi bi-gear-fill me-2"></i>Acción</th>
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
                                        <i class="bi bi-check-circle"></i> Seleccionar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted text-center">
                                    <i class="bi bi-inbox"></i> No se encontraron resultados
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
            <div class="card-header bg-secondary text-white fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-person-check-fill"></i>
                <span>Alumno seleccionado</span>
            </div>

            <div class="card-body">
                <div class="datos-alumno mb-4">
                    <label class="form-label"><i class="bi bi-person-vcard"></i> Nombre completo</label>
                    <div class="nombre-alumno">{{ $alumnoSeleccionado->apellido }}, {{ $alumnoSeleccionado->nombre }}</div>
                </div>

                <div class="bloque-carrera mb-3">
                    <label for="carreraSeleccionada" class="form-label"><i class="bi bi-mortarboard"></i> Carrera</label>
                    <select id="carreraSeleccionada" wire:model="carreraSeleccionada" wire:change="activarBoton" class="form-select carrera-select">
                        <option value="">Seleccionar una carrera...</option>
                        @foreach ($alumnoSeleccionado->egresadoinscripto as $egresado)
                            <option value="{{ $egresado->id_carrera }}">{{ $egresado->carrera->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($mostrarBoton)
                    <div class="text-end">
                        <button wire:click="verMaterias" class="btn btn-primary btn-ver-materias">
                            <i class="bi bi-journal-text"></i> Ver materias
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- 📚 Asignaturas disponibles --}}
    @if ($materiasCarrera && count($materiasCarrera))
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white fw-bold">
                <i class="bi bi-journal-bookmark-fill"></i> Asignaturas disponibles
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-book me-2"></i>Asignatura</th>
                                <th class="text-center" style="width: 120px;"><i class="bi bi-check-square me-2"></i>Seleccionar</th>
                                <th style="width: 200px;"><i class="bi bi-clipboard-check me-2"></i>Condición</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materiasCarrera as $asignatura)
                                <tr wire:key="asignatura-{{ $asignatura->id }}">
                                    <td class="fw-semibold">{{ $asignatura->nombre }}</td>
                                    <td class="text-center">
                                        <input type="checkbox" wire:model="asignaturasSeleccionadas" value="{{ (int)$asignatura->id }}">
                                    </td>
                                    <td>
                                        <select wire:model="condiciones.{{ $asignatura->id }}" class="form-select form-select-sm">
                                            <option value="">Seleccionar condición...</option>
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
                @if ($mensaje)
                    <div class="alert alert-success mt-3">
                        <i class="bi bi-check-circle-fill"></i> {{ $mensaje }}
                    </div>
                @endif

                @if (count($erroresValidacion))
                    <div class="alert alert-danger mt-3">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Errores encontrados:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($erroresValidacion as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif
      {{-- 🔘 Bloque de botones (Cancelar siempre visible, Guardar solo si corresponde) --}}
                <div class="botones-derecha mt-4 d-flex justify-content-end gap-2">
                    <x-botones-alumno />
                    <x-btn-cancelar />

                    @if ($materiasCarrera && count($materiasCarrera))
                        <button wire:click="guardarCursada" class="btn_blue">
                            <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                            Guardar cursadas
                        </button>
                    @endif
                </div>

</div>
