<div class="perfil_one br">
    <div class="perfil__header">
        <h2>Asignaturas asignadas</h2>
    </div>

    @if ($profesor->asignaturas->isEmpty())
        <p class="text-muted" style="margin: 10px">Este profesor aún no tiene asignaturas vinculadas.</p>
    @else
        @php
            // Agrupamos asignaturas por carrera
            $asignaturasPorCarrera = $profesor->asignaturas->groupBy(function ($asig) {
                return $asig->pivot->id_carrera;
            });
        @endphp

        @foreach ($asignaturasPorCarrera as $idCarrera => $asignaturas)
            @php
                $carrera = \App\Models\Carrera::find($idCarrera);
                // Agrupamos por año dentro de cada carrera
                $porAnio = $asignaturas->groupBy(fn($a) => $a->pivot->anio ?? 'Sin año');
            @endphp

            <div class="card mb-4 shadow-sm p-3">
                <h4 class="mb-3 text-primary border-bottom pb-2">
                    {{ $carrera?->nombre ?? 'Carrera desconocida' }}</h4>

                <div class="accordion" id="accordionCarrera{{ $idCarrera }}">
                    @foreach ($porAnio as $anio => $lista)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $idCarrera }}-{{ $anio }}">
                                <button class="accordion-button collapsed font-500" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $idCarrera }}-{{ $anio }}"
                                    aria-expanded="false"
                                    aria-controls="collapse{{ $idCarrera }}-{{ $anio }}">
                                    {{ is_numeric($anio) ? $anio . '° año' : $anio }}
                                </button>
                            </h2>
                            <div id="collapse{{ $idCarrera }}-{{ $anio }}"
                                class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $idCarrera }}-{{ $anio }}"
                                data-bs-parent="#accordionCarrera{{ $idCarrera }}">
                                <div class="accordion-body p-0">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Asignatura</th>
                                                <th>Módulo</th>
                                                <th>Carga horaria</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lista as $asignatura)
                                                @php
                                                    $pivot = $asignatura->pivot;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ $asignatura->nombre }}
                                                        @if ($asignatura->correlativas->isNotEmpty())
                                                            <span class="badge bg-info ms-2">📎</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $pivot->tipo_modulo ?? '—' }}</td>
                                                    <td>{{ $pivot->carga_horaria ?? '—' }} hs</td>
                                                    <td style="white-space: nowrap;">
                                                        <button class="btn btn-sm btn-outline-secondary me-1"
                                                            onclick="vincularCorrelativa({{ $asignatura->id }})">
                                                            Vincular correlativa
                                                        </button>

                                                        @if ($asignatura->dependientes->isNotEmpty())
                                                            <button class="btn btn-sm btn-outline-info"
                                                                onclick="mostrarDependencias({{ $asignatura->id }})">
                                                                Ver dependencias
                                                            </button>
                                                        @endif
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
        @endforeach
    @endif
</div>
