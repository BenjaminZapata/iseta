@extends('Secretario.template')

@section('content')
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'Detalle de Alumno/a'])

        {{-- DATOS DEL ALUMNO --}}
        <div class="datos-alumno">
            <h3>Información personal</h3>
            <p><strong>Nombre:</strong> {{ $alumno->nombre }}</p>
            <p><strong>Apellido:</strong> {{ $alumno->apellido }}</p>
            <p><strong>DNI:</strong> {{ $alumno->dni }}</p>
            <p><strong>Fecha de nacimiento:</strong> {{ $alumno->fecha_nacimiento?->format('d/m/Y') }}</p>
            <p><strong>Estado civil:</strong> {{ $alumno->estado_civil }}</p>
            <p><strong>Género:</strong> {{ $alumno->genero }}</p>

            <h3>Dirección</h3>
            <p><strong>Ciudad:</strong> {{ $alumno->ciudad }}</p>
            <p><strong>Código postal:</strong> {{ $alumno->codigo_postal }}</p>
            <p><strong>Calle:</strong> {{ $alumno->calle }} {{ $alumno->casa_numero }}</p>
            @if($alumno->dpto) <p><strong>Dpto:</strong> {{ $alumno->dpto }}</p> @endif
            @if($alumno->piso) <p><strong>Piso:</strong> {{ $alumno->piso }}</p> @endif

            <h3>Contacto</h3>
            <p><strong>Email:</strong> {{ $alumno->email }}</p>
            <p><strong>Teléfonos:</strong> {{ $alumno->telefono1 }} {{ $alumno->telefono2 }} {{ $alumno->telefono3 }}</p>

            <h3>Académico</h3>
            <p><strong>Título anterior:</strong> {{ $alumno->titulo_anterior }}</p>
            <p><strong>Becas:</strong> {{ $alumno->becas }}</p>
            <p><strong>Institución secundaria:</strong> {{ $alumno->nombre_institucion_secundario }}</p>
            <p><strong>Título secundario:</strong> {{ $alumno->titulo_secundario }}</p>

            @if($alumno->observaciones)
                <h3>Otros</h3>
                <p><strong>Observaciones:</strong> {{ $alumno->observaciones }}</p>
            @endif
        </div>
    </div>

    <!--//? CURSADAS -->
    <div class="edit-form-container">
        <div class="table">
            <div class="table__header">
                <h2>Cursadas</h2>
            </div>

            <div class="accordion" id="cursadasAccordion">
                @php
                    $carrera_actual = '';
                    $anio_actual = '';
                    $carrera_index = 0;
                    $anio_index = 0;
                @endphp

                @foreach ($cursadas as $cursada)
                    @if ($carrera_actual != $cursada->carrera)
                        @if ($carrera_actual != '')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingCarrera{{ $carrera_index }}">
                        <button class="accordion-button collapsed font-500" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseCarrera{{ $carrera_index }}" aria-expanded="false"
                            aria-controls="collapseCarrera{{ $carrera_index }}">
                            {{ $cursada->carrera }}
                        </button>
                    </h2>
                    <div id="collapseCarrera{{ $carrera_index }}" class="accordion-collapse collapse"
                        aria-labelledby="headingCarrera{{ $carrera_index }}" data-bs-parent="#cursadasAccordion">
                        <div class="accordion-body p-2">
                            @php
                                $carrera_actual = $cursada->carrera;
                                $anio_actual = '';
                            @endphp
                        @endif

                        @if ($anio_actual != $cursada->anio_asig)
                            @if ($anio_actual != '')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                            @endif

                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingAnio{{ $carrera_index }}-{{ $anio_index }}">
                                    <button class="accordion-button collapsed font-500" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseAnio{{ $carrera_index }}-{{ $anio_index }}" aria-expanded="false"
                                        aria-controls="collapseAnio{{ $carrera_index }}-{{ $anio_index }}">
                                        {{ $cursada->anio_asig + 1 }}° año
                                    </button>
                                </h3>
                                <div id="collapseAnio{{ $carrera_index }}-{{ $anio_index }}" class="accordion-collapse collapse"
                                    aria-labelledby="headingAnio{{ $carrera_index }}-{{ $anio_index }}">
                                    <div class="accordion-body p-0">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Materia</th>
                                                    <th>Condición</th>
                                                    <th class="center">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table__body">
                                                @php
                                                    $anio_actual = $cursada->anio_asig;
                                                @endphp
                        @endif

                        <tr>
                            <td>{{ $cursada->asignatura }}</td>
                            <td>{{ $cursada->condicionString() }}</td>
                            <td class="center">{{ $cursada->aprobado() }}</td>
                        </tr>
                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
            </div>
        </div>
    </div>

    <!--//? EXÁMENES -->
    <div class="table">
        <div class="table__header">
            <h2>Exámenes</h2>
            <p>Importante: algunos exámenes antiguos podrían no tener datos sobre las mesas.</p>
        </div>

        <div class="accordion" id="examenesAccordion">
            @php
                $carrera_actual = '';
                $anio_actual = '';
                $carrera_index = 0;
                $anio_index = 0;
            @endphp

            @foreach ($examenes as $examen)
                @if ($carrera_actual != $examen->carrera)
                    @if ($carrera_actual != '')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    @endif

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingExamenCarrera{{ $carrera_index }}">
                            <button class="accordion-button collapsed font-500" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseExamenCarrera{{ $carrera_index }}" aria-expanded="false"
                                aria-controls="collapseExamenCarrera{{ $carrera_index }}">
                                {{ $examen->carrera }}
                            </button>
                        </h2>
                        <div id="collapseExamenCarrera{{ $carrera_index }}" class="accordion-collapse collapse"
                            aria-labelledby="headingExamenCarrera{{ $carrera_index }}" data-bs-parent="#examenesAccordion">
                            <div class="accordion-body p-2">
                                @php
                                    $carrera_actual = $examen->carrera;
                                    $anio_actual = '';
                                @endphp
                @endif

                @if ($anio_actual != $examen->anio_asig)
                    @if ($anio_actual != '')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    @endif

                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingExamenAnio{{ $carrera_index }}-{{ $anio_index }}">
                            <button class="accordion-button collapsed font-500" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseExamenAnio{{ $carrera_index }}-{{ $anio_index }}"
                                aria-expanded="false"
                                aria-controls="collapseExamenAnio{{ $carrera_index }}-{{ $anio_index }}">
                                {{ $examen->anio_asig + 1 }}° año
                            </button>
                        </h3>
                        <div id="collapseExamenAnio{{ $carrera_index }}-{{ $anio_index }}" class="accordion-collapse collapse"
                            aria-labelledby="headingExamenAnio{{ $carrera_index }}-{{ $anio_index }}">
                            <div class="accordion-body p-0">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Materia</th>
                                            <th>Fecha</th>
                                            <th>Nota</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $anio_actual = $examen->anio_asig;
                                        @endphp
                @endif

                <tr>
                    <td>{{ $examen->asignatura }}</td>
                    <td>{{ $formatoFecha->dma($examen->fecha()) }}</td>
                    <td>
                        @if ($examen->aprobado == 3)
                            Ausente
                        @elseif($examen->nota <= 0)
                            Sin nota
                        @else
                            {{ $examen->nota }}
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
    </div>
@endsection
