<div class="p-4">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
{{-- FILTROS --}}
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


    {{-- SELECCIÓN DE CARRERA --}}
@if ($alumnoSeleccionado)
    <h5 class="mt-4">Alumno: {{ $alumnoSeleccionado->apellido }}, {{ $alumnoSeleccionado->nombre }}</h5>

    <div class="mt-3">
        <label for="carrera">Carrera</label>
        <select wire:model="carreraSeleccionada" class="form-select">
            <option value="">Seleccionar...</option>
            @foreach ($carreras as $carrera)
                <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
            @endforeach
        </select>
    </div>
@endif


    {{-- SELECCIÓN DE ASIGNATURAS --}}
    @if ($carreraSeleccionada)
        <div class="mt-3">
            <h5>Asignaturas</h5>
            @foreach ($asignaturas as $asignatura)
                <div class="border p-2 mb-2 rounded">
                    <input type="checkbox" wire:model="asignaturasSeleccionadas" value="{{ $asignatura->id }}">
                    {{ $asignatura->nombre }}

                    @if (in_array($asignatura->id, $asignaturasSeleccionadas))
                        <select wire:model="condiciones.{{ $asignatura->id }}" class="form-select mt-1">
                            <option value="">Condición...</option>
                            <option value="0">Libre</option>
                            <option value="1">Regular</option>
                            <option value="2">Itinerante</option>
                            <option value="3">Oyente</option>
                        </select>
                    @endif
                </div>
            @endforeach
        </div>

        <button wire:click="registrar" class="btn btn-success mt-3">Registrar inscripción</button>
    @endif

    {{-- ERRORES --}}
    @if ($erroresValidacion)
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($erroresValidacion as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
