@extends('Admin.template')

@section(section: 'content')
    <div class="perfil_one br">
        <div class="perfil__header">
            <h2>Modificar Alumno</h2>
        </div>

        <?= $form->generate(route('admin.alumnos.update', ['alumno' => $alumno->id]), 'put', [
        'Alumno' => [
            $form->text('nombre', 'Nombre:', 'label-input-y-75', $alumno),
            $form->text('apellido', 'Apellido:', 'label-input-y-75', $alumno),
            $form->text('dni', 'DNI:', 'label-input-y-75', $alumno),
            $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', $alumno, ['default' => $alumno->fecha_nacimiento->format('Y-m-d'), 'inputclass' => 'p-1 w-75p']),
            $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', $alumno, ['Vacio', 'Soltero', 'Casado', 'Divorciado', 'Viudo', 'Conyuge', 'Otro']),
            $form->select('genero', 'Género:', 'label-input-y-75', $alumno, ['Vacio', 'Masculino', 'Femenino', 'Otro'])
        ],
        'Dirección' => [
            $form->text('ciudad', 'Ciudad:', 'label-input-y-75', $alumno),
            $form->text('codigo_postal', 'Codigo postal:', 'label-input-y-75', $alumno),
            $form->text('calle', 'Calle:', 'label-input-y-75', $alumno),
            $form->text('casa_numero', 'Altura:', 'label-input-y-75', $alumno),
            $form->text('dpto', 'Departamento:', 'label-input-y-75', $alumno),
            $form->text('piso', 'Piso:', 'label-input-y-75', $alumno)
        ],
        'Contacto' => [
            $form->text('email', 'Email:', 'label-input-y-75', $alumno),
            $form->text('telefono1', 'Telefono 1:', 'label-input-y-75', $alumno),
            $form->text('telefono2', 'Telefono 2:', 'label-input-y-75', $alumno),
            $form->text('telefono3', 'Telefono 3:', 'label-input-y-75', $alumno)
        ],
        'Academico' => [
            $form->text('titulo_anterior', 'Titulo anterior:', 'label-input-y-75', $alumno),
            $form->text('becas', 'Becas:', 'label-input-y-75', $alumno),
            $form->text('nombre_institucion_secundario', 'Secundaria:', 'label-input-y-75', $alumno),
            $form->select('titulo_secundario', 'Titulo secundario:', 'label-input-y-75', $alumno, [
                0 => 'Fotocopia del título original secundario',
                1 => 'Certificado de constancia de título en trámite',
                2 => 'Constancia de alumno del último año del nivel secundario',
                3 => 'No entregado',
                4 => 'Otro'
            ])
        ],
        'Otros' => [$form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', $alumno)]
    ]) ?>
    </div>

    </div>

    <div class="matricular">
        <form action="{{ route('admin.alumno.rematricular', ['alumno' => $alumno->id]) }}">
            <select name="carrera">
                @foreach ($carreras as $carrera)
                    <option value="{{$carrera->carrera_id}}">{{$carrera->carrera_nombre}}</option>
                @endforeach
            </select>
            <div class="upd"><button class="btn_blue"><i class="ti ti-paperclip"></i>Matricular</button></div>
        </form>
        <a href="{{ route('admin.inscriptos.create') }}" style="display:block;width:190px"><button class="btn_blue"
                style="margin-top:-40px">Inscribir a otra carrera</button></a>
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
                                        </div>
                                        </div>
                                        </div>
                                        @php $carrera_index++; @endphp
                                        @php $anio_index = 0; @endphp
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
                                        @php $anio_index++; @endphp
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
                                                <th>Condicion</th>
                                                <th class="center">Estado</th>
                                                <th class="center">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table__body">
                                            @php
                                                $anio_actual = $cursada->anio_asig;
                                            @endphp
                    @endif

                            <tr data-name="MateriaCursada">
                                <td>{{ $cursada->asignatura }}</td>
                                <td>{{ $cursada->condicionString() }}</td>
                                <td class="center">{{ $cursada->aprobado() }}</td>
                                <td class="flex just-center">
                                    <a href="{{ route('admin.cursadas.edit', ['cursada' => $cursada->id]) }}">
                                        <button class="btn_blue"><i class="ti ti-edit"></i>Editar</button>
                                    </a>
                                </td>
                            </tr>
                @endforeach

                    </tbody>
                </table>


            </div>

            <div class="table">
                <div class="table__header">
                    <h2>Examenes</h2>
                    <p>Importante: algunos examanes de alumnos mas antiguos podrian no tener datos sobre las mesas.</p>
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
                                                    </div>
                                                    </div>
                                                    </div>
                                                @endif

                                @php
                                    $carrera_index++;
                                    $anio_index = 0;
                                @endphp

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

                            @php
                                $anio_index++;
                            @endphp

                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingExamenAnio{{ $carrera_index }}-{{ $anio_index }}">
                                    <button class="accordion-button collapsed font-500" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseExamenAnio{{ $carrera_index }}-{{ $anio_index }}" aria-expanded="false"
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
                                                    <th class="center">Acción</th>
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
                                    <td class="flex just-center">
                                        <a href="{{ route('admin.examenes.edit', ['examen' => $examen->id]) }}">
                                            <button class="btn_blue"><i class="ti ti-edit"></i>Editar</button>
                                        </a>
                                    </td>
                                </tr>
                    @endforeach
                    </tbody>
                </table>
                @if ($alumno->verificado == 0)
                                <div class='my-4'>
                                    <a href="{{route('admin.alumnos.verificar', ['alumno' => $alumno->id])}}"><button
                                            class="btn_blue">Verificar alumno</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    </div>
                @endif
    </div>

    @if ($alumno->verificado == 0)
        <div class='my-4 mx-2'>
            <a href="{{ route('admin.alumnos.verificar', ['alumno' => $alumno->id]) }}">
                <button class="btn_blue">Verificar alumno</button>
            </a>
        </div>
    @endif
    </div>
@endsection