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
                    <tr wire:key="alumno-{{ $alumno->id }}">
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
                    <tr>
                        <td colspan="4" class="text-center">
                            <span x-intersect="$wire.nextPage">
                                Cargando alumnos...
                            </span>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
</div>
