@extends('Admin.template')

@section(section: 'content')
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR ALUMNO/A'])
        <?= $form->generate(route('admin.alumnos.update', ['alumno' => $alumno->id]), 'put', [
                'Alumno' => [
                    $form->text('nombre', 'Nombre:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: Juan']),
                    $form->text('apellido', 'Apellido:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: Pérez']),
                    $form->text('dni', 'DNI:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 12345678']),
                    $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', $alumno, [
                        'default' => $alumno->fecha_nacimiento->format('Y-m-d'),
                        'inputclass' => 'p-1 w-75p',
                        'placeholder' => 'Formato: dd/mm/aaaa',
                    ]),
                    $form->text('lugar_nacimiento', 'Ciudad de nacimiento:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: Córdoba']),
                    $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', $alumno, [
                        '' => 'Seleccione una opción',
                        'Soltero',
                        'Casado',
                        'Divorciado',
                        'Viudo',
                        'Conyuge',
                        'Otro',
                    ]),
                ],
                'Dirección' => [$form->text('ciudad', 'Ciudad:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 9 de Julio']), $form->text('codigo_postal', 'Código postal:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 6500']), $form->text('calle', 'Calle:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: Av. Eva Perón']), $form->text('casa_numero', 'Altura:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 742']), $form->text('dpto', 'Departamento:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: A']), $form->text('piso', 'Piso:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 3'])],
                'Contacto' => [$form->text('email', 'Email:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: ejemplo@dominio.com']), $form->text('telefono1', 'Teléfono 1:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 2317-876544']), $form->text('telefono2', 'Teléfono 2:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 2317-876543'])],
                'Académico' => [
                    $form->text('titulo_anterior', 'Título anterior:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: Técnico en Informática']),
                    $form->text('becas', 'Cantidad de becas:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 2']),
                    $form->text('nombre_institucion_secundario', 'Nombre de institución Secundaria:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: Escuela Nacional N°1']),
                    $form->select('titulo_secundario', 'Título secundario:', 'label-input-y-75', $alumno, [
                        '' => 'Seleccione una opción',
                        'No entregado',
                        'Certificado de constancia de título en trámite',
                        'Constancia de alumno del último año del nivel secundario',
                        'Fotocopia del título original secundario',
                    ]),
                ],
                'Otros' => [$form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', $alumno, ['placeholder' => 'Notas o comentarios adicionales'])],
            ]) ?>


        <div class="boton-eliminar">
            @if (!$config['modo_seguro'])
                <div>
                    @if (!$config['modo_seguro'])
                        <form id="form-eliminar-{{ $alumno->id }}"
                            action="{{ route('admin.alumnos.destroy', $alumno->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                onclick="openGeneralModal('form-eliminar-{{ $alumno->id }}',
                                    '¿Estás seguro de que querés eliminar al alumno: {{ strtoupper($alumno->apellido) }} {{ strtoupper($alumno->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                class="btn_red_outline">
                                <i class="ti ti-trash" style="font-size: 1.3em; margin-right: 8px;"></i> Eliminar alumno
                            </button>

                        </form>
                    @endif
                </div>
            @endif
        </div>

        {{-- HEADER PARA VERIFICAR ALUMNO 

    @if ($alumno->verificado == 0)
    <div class="perfil_one br">
        <div class="perfil__header">
            <h2>Validar alumno</h2>
        </div>
        <div>
            <p style="padding: 16px 27px 0 27px; font-weight: bold;">Al hacer click en validar alumno se enviará un
                mail al alumno con
                su usuario y contraseña para
                ingresar
                al
                sistema. Si el alumno no está verificado, no podrá acceder al mismo. </p>

            <div class='botones-derecha'
                style="margin-right: 27px; padding-top: 10px; padding-bottom: 16px; display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ route('admin.alumnos.verificar', ['alumno' => $alumno->id]) }}"><button class="btn_blue"
        title="Enviar mail al alumno con el usuario y la contraseña"><i class="ti ti-check"
            style="font-size: 1.3em; margin-right: 8px;"></i>Validar
        alumno</button></a>
</div>
</div>
@endif
</div>

--}}

        {{-- CARRERAS --}}

        {{-- CARRERAS --}}
        <div class="table mb-5">
            <div class="table__header">
                <h2>CARRERAS</h2>
            </div>

            <div class="matricular">

                <form>
                    @csrf
                    <table class="table table-bordered table-hover mb-0 text-center">
                        <thead>
                            <tr>
                                <th>Nombre de la carrera</th>
                                <th class="center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($carreras as $carrera)
                                <tr>
                                    <td class="bold">{{ $carrera->carrera_nombre }}</td>
                                    <td>
                                        <select name="estados[{{ $carrera->carrera_id }}]" class="form-select text-center">
                                            <option value="Cursando" {{ $carrera->estado == 'Cursando' ? 'selected' : '' }}>
                                                Cursando</option>
                                            <option value="Egresado" {{ $carrera->estado == 'Egresado' ? 'selected' : '' }}>
                                                Egresado</option>
                                            <option value="Inactivo" {{ $carrera->estado == 'Inactivo' ? 'selected' : '' }}>
                                                Inactivo</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>

                <div class="text-center" style="margin-top:20px;">
                    <a href="{{ route('admin.inscriptos.create', ['alumno_id' => $alumno->id]) }}">
                        <button class="btn_blue">
                            <i class="ti ti-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                            Inscribir a otra carrera
                        </button>
                    </a>
                </div>

            </div>
        </div>



        {{-- REMATRICULACIÓN MANUAL --}}
        <div class="table mb-5">
            <div class="table__header">
                <h2>Rematriculación manual</h2>
            </div>

            <div class="matricular">
                <form action="{{ route('admin.alumno.rematricular', ['alumno' => $alumno->id]) }}">
                    <select name="carrera">
                        @foreach ($carreras as $carrera)
                            <option value="{{ $carrera->carrera_id }}">{{ $carrera->carrera_nombre }}</option>
                        @endforeach
                    </select>
                    <div class="upd">
                        <button class="btn_blue">
                            <i class="ti ti-paperclip" style="font-size: 1.3em; margin-right: 8px;"></i>
                            Matricular
                        </button>
                    </div>
                </form>
                <a href="{{ route('admin.inscriptos.create', ['alumno_id' => $alumno->id]) }}"
                    style="display:block;width:190px">
                    <button class="btn_blue" style="margin-top:-40px"><i class="ti ti-plus"
                            style="font-size: 1.3em; margin-right: 8px;"></i>Inscribir a otra carrera</button>
                </a>
            </div>
        </div>
        {{-- CURSADAS --}}
        <div class="table mb-5">
            <div class="table__header">
                <h2>Cursadas</h2>
            </div>
            <div class="accordion" id="accordionCursadas">
                @php
                    $agrupadasCursadas = collect($cursadas)->groupBy(fn($c) => $c->carrera);
                @endphp

                @foreach ($agrupadasCursadas as $carrera => $porCarrera)
                    @php
                        $porAnio = $porCarrera->groupBy('anio_asig')->sortKeys();
                    @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingCarreraCursadas{{ $loop->index }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseCarreraCursadas{{ $loop->index }}" aria-expanded="false">
                                {{ $carrera }}
                            </button>
                        </h2>
                        <div id="collapseCarreraCursadas{{ $loop->index }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionCursadas">
                            <div class="accordion-body p-2">
                                <div class="accordion" id="anioAccordionCursadas{{ $loop->index }}">
                                    @foreach ($porAnio as $anio => $cursadasDelAnio)
                                        <div class="accordion-item">
                                            <h3 class="accordion-header"
                                                id="headingCursada{{ $loop->parent->index }}-{{ $loop->index }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseCursada{{ $loop->parent->index }}-{{ $loop->index }}"
                                                    aria-expanded="false">
                                                    {{ ((int) $anio) + 1 }}° año
                                                </button>
                                            </h3>
                                            <div id="collapseCursada{{ $loop->parent->index }}-{{ $loop->index }}"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#anioAccordionCursadas{{ $loop->parent->index }}">
                                                <div class="accordion-body p-0">
                                                    <table class="table table-bordered table-hover mb-0 text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>Materia</th>
                                                                <th class="center">Condición</th>
                                                                <th class="center">Estado</th>
                                                                <th class="center">Acción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($cursadasDelAnio as $cursada)
                                                                <tr>
                                                                    <td class="bold">{{ $cursada->asignatura }}</td>
                                                                    <td>
                                                                        <div
                                                                            style="display: flex; justify-content: center;">
                                                                            {{ $cursada->condicionString() }}
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div
                                                                            style="display: flex; justify-content: center;">
                                                                            {{ $cursada->aprobado() }}
                                                                        </div>

                                                                    </td>
                                                                    <td class="flex just-center" style="min-width: 170px;">
                                                                        <div
                                                                            style="display: flex; justify-content: center; gap:10px">
                                                                            <a
                                                                                href="{{ route('admin.cursadas.edit', $cursada->id) }}">
                                                                                <button class="btn_blue btn_contraible">
                                                                                    <i class="ti ti-pencil"
                                                                                        style="font-size: 1.3em;"></i>
                                                                                    <span class="btn-text">Editar</span>
                                                                                </button>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div> {{-- Fin año --}}
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div> {{-- Fin carrera --}}
                @endforeach
            </div>
        </div>

        {{-- EXÁMENES --}}
        <div class="table">
            <div class="table__header">
                <h2>Exámenes</h2>
            </div>
            <div class="accordion" id="accordionExamenes">
                @php
                    $agrupadasExamenes = collect($examenes)->groupBy(fn($e) => $e->carrera);
                @endphp

                @foreach ($agrupadasExamenes as $carrera => $porCarrera)
                    @php
                        $porAnio = $porCarrera->groupBy('anio_asig')->sortKeys();
                    @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingCarreraExamenes{{ $loop->index }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseCarreraExamenes{{ $loop->index }}" aria-expanded="false">
                                {{ $carrera }}
                            </button>
                        </h2>
                        <div id="collapseCarreraExamenes{{ $loop->index }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExamenes">
                            <div class="accordion-body p-2">
                                <div class="accordion" id="anioAccordionExamenes{{ $loop->index }}">
                                    @foreach ($porAnio as $anio => $examenesDelAnio)
                                        <div class="accordion-item">
                                            <h3 class="accordion-header"
                                                id="headingExamen{{ $loop->parent->index }}-{{ $loop->index }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseExamen{{ $loop->parent->index }}-{{ $loop->index }}"
                                                    aria-expanded="false">
                                                    {{ ((int) $anio) + 1 }}° año
                                                </button>
                                            </h3>
                                            <div id="collapseExamen{{ $loop->parent->index }}-{{ $loop->index }}"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#anioAccordionExamenes{{ $loop->parent->index }}">
                                                <div class="accordion-body p-0">
                                                    <table class="table table-bordered table-hover mb-0 text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>Materia</th>
                                                                <th class="center">Fecha</th>
                                                                <th class="center">Nota</th>
                                                                <th class="center">Acción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($examenesDelAnio as $examen)
                                                                <tr>
                                                                    <td class="bold">{{ $examen->asignatura }}</td>
                                                                    <td>
                                                                        <div
                                                                            style="display: flex; justify-content: center; gap: 10px;">
                                                                            {{ $formatoFecha->dma($examen->fecha()) }}
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        @if ($examen->aprobado == 3)
                                                                            AUSENTE
                                                                        @elseif($examen->nota <= 0)
                                                                            <div
                                                                                style="display: flex; justify-content: center; gap: 10px;">
                                                                                <span class="bold"
                                                                                    style="color: red">SIN NOTA</span>
                                                                            </div>
                                                                        @else
                                                                            @if ($examen->nota >= 4)
                                                                                <div
                                                                                    style="display: flex; justify-content: center; gap: 10px;">
                                                                                    <span class="bold"
                                                                                        style="color: green;">{{ $examen->nota }}</span>
                                                                                </div>
                                                                            @else
                                                                                <div
                                                                                    style="display: flex; justify-content: center; gap: 10px;">
                                                                                    <span class="bold"
                                                                                        style="color: red;">{{ $examen->nota }}</span>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                    <td class="flex just-center"
                                                                        style="min-width: 170px;">
                                                                        <div
                                                                            style="display: flex; justify-content: center; gap: 10px;">
                                                                            <a
                                                                                href="{{ route('admin.examenes.edit', $examen->id) }}">
                                                                                <button class="btn_blue btn_contraible">
                                                                                    <i class="ti ti-pencil"
                                                                                        style="font-size: 1.3em;"></i>
                                                                                    <span class="btn-text">Editar</span>
                                                                                </button>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div> {{-- Fin año --}}
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div> {{-- Fin carrera --}}
                @endforeach
            </div>
        </div>
    @endsection
