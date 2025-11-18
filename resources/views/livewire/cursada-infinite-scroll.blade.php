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
                @for ($i = 0; $i < count($alumnos_total); $i++) 
                    <tr wire:key="alumno-{{ $alumnos_total[$i]->id }}"></tr>
                        <td class="bold">
                            <div class="centrar">{{ $alumnos_total[$i]->apellido }}</div>
                        </td>
                        <td class="bold">
                            <div class="centrar">{{ $alumnos_total[$i]->nombre }}</div>
                        </td>
                        <td class="bold">
                            <div class="centrar">{{ $alumnos_total[$i]->dni }}</div>
                        </td>
                        <td class="center">
                            <div class="centrar">
                                <button type="button" wire:click="seleccionarAlumno({{ $alumnos_total[$i]->id }})"
                                    class="btn btn-modificar">
                                    Seleccionar
                                </button>
                            </div>

                        </td>
                    </tr>
                @endfor
                @empty($alumnos_total)
                    <tr>
                        <td colspan="4" class="text-center text-muted">No se encontraron resultados</td>
                    </tr>
                @endempty
                @empty(!$alumnos_total)
                    <tr>
                        <td colspan="4" class="text-center" x-intersect="$wire.loadMore">
                            <span wire:loading>
                                Cargando alumnos...
                            </span>
                        </td>
                    </tr>
                @endempty
            </tbody>
        </table>
    </div>
</div>
