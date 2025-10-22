<div class="p-3">

    {{-- FILTROS DE ALUMNO --}}
    @if (auth()->user()->rol !== 'Alumno')
        <div class="mb-3">
            <h4>Buscar alumno</h4>
            <div class="d-flex gap-2">
                <input type="text" wire:model.live="nombre" placeholder="Nombre" class="form-control" maxlength="30">
                <input type="text" wire:model.live="apellido" placeholder="Apellido" class="form-control" maxlength="30">
                <input type="text" wire:model.live="dni" placeholder="DNI" class="form-control" maxlength="10">
            </div>

            @if (($apellido && $nombre) || $dni)
                <ul class="list-group mt-2">
                    @forelse ($alumnos as $alumno)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $alumno->apellido }}, {{ $alumno->nombre }} (DNI: {{ $alumno->dni }})
                            <button wire:click="seleccionarAlumno({{ $alumno->id }})" class="btn btn-sm btn-primary">
                                Seleccionar
                            </button>
                        </li>
                    @empty
                        <li class="list-group-item">Sin resultados</li>
                    @endforelse
                </ul>
            @endif
        </div>
    @endif

  <div class="mt-3 d-flex gap-2 align-items-center">
    <label for="carrera">Carrera</label>
    <select wire:model="carreraSeleccionada" class="form-select">
        <option value="">Seleccionar...</option>
        @foreach ($carreras as $carrera)
            <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
        @endforeach
    </select>

    {{-- Mostrar siempre el botón si hay algo seleccionado --}}
    @if ($carreraSeleccionada)
        <button wire:click="cargarMaterias" class="btn btn-primary">
            Ver materias
        </button>
    @endif
</div>
    {{-- SELECCIÓN DE ASIGNATURAS --}}
    @if (!empty($materiasCarrera))
        <table class="table table-bordered align-middle mt-3">
            <thead class="table-light">
                <tr>
                    <th>Asignatura</th>
                    <th>Seleccionar</th>
                    <th>Condición</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($materiasCarrera as $asignatura)
                    <tr>
                        <td>{{ $asignatura->nombre }}</td>
                        <td>
                            <input type="checkbox" wire:model="asignaturasSeleccionadas" value="{{ $asignatura->id }}">
                        </td>
                        <td>
                            @if (in_array($asignatura->id, $asignaturasSeleccionadas))
                                <select wire:model="condiciones.{{ $asignatura->id }}" class="form-select">
                                    <option value="">Condición...</option>
                                    <option value="Regular">Regular</option>
                                    <option value="Libre">Libre</option>
                                    <option value="Promocional">Promocional</option>
                                </select>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button wire:click="registrar" class="btn btn-success mt-3">
            Registrar cursada
        </button>
    @endif

    {{-- ERRORES --}}
    @if ($erroresValidacion)
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach ($erroresValidacion as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- MENSAJE ÉXITO --}}
    @if (session()->has('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
</div>
