@extends('Admin.template')
@php
use Illuminate\Support\Facades\Auth;
$admin = Auth::guard('admin')->user();
$rol = auth()->user()->rol ?? null;
$disabled = $rol === 'secretario' ? 'disabled' : false;
$mostrar_botones = $rol !== 'secretario'; // oculta botones si es secretario
@endphp
@section(section: 'content')
<link rel="stylesheet" href="{{ asset('css/Admin/main.css') }}">
<div class="perfil_one br">
    @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR ALUMNO/A'])
    <?= $form->generate(route('admin.alumnos.update', ['alumno' => $alumno->id]), 'put', [
        'Alumno' => [
            $form->text('nombre', 'Nombre:', 'label-input-y-75', $alumno, [
                'placeholder' => 'Ej: Juan',
                $disabled => $disabled
            ]),
            $form->text('apellido', 'Apellido:', 'label-input-y-75', $alumno, [
                'placeholder' => 'Ej: Pérez',
                $disabled => $disabled
            ]),
            $form->text('dni', 'DNI:', 'label-input-y-75', $alumno, [
                'placeholder' => 'Ej: 12345678',
                $disabled => $disabled
            ]),
            $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', $alumno, [
                'default' => $alumno->fecha_nacimiento->format('Y-m-d'),
                'inputclass' => 'p-1 w-75p',
                'placeholder' => 'Formato: dd/mm/aaaa',
                $disabled => $disabled
            ]),
            $form->text('lugar_nacimiento', 'Ciudad de nacimiento:', 'label-input-y-75', $alumno, [
                'placeholder' => 'Ej: Córdoba',
                $disabled => $disabled
            ]),
            $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', $alumno, [
                '' => 'Seleccione una opción',
                'Soltero',
                'Casado',
                'Divorciado',
                'Viudo',
                'Conyuge',
                'Otro',
            ], [
                $disabled => $disabled
            ]),
        ],
        'Dirección' => [
            $form->text('ciudad', 'Ciudad:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 9 de Julio', $disabled => $disabled]),
            $form->text('codigo_postal', 'Código postal:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 6500', $disabled => $disabled]),
            $form->text('calle', 'Calle:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: Av. Eva Perón', $disabled => $disabled]),
            $form->text('casa_numero', 'Altura:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 742', $disabled => $disabled]),
            $form->text('dpto', 'Departamento:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: A', $disabled => $disabled]),
            $form->text('piso', 'Piso:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 3', $disabled => $disabled]),
        ],
        'Contacto' => [
            $form->text('email', 'Email:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: ejemplo@dominio.com', $disabled => $disabled]),
            $form->text('telefono1', 'Teléfono 1:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 2317-876544', $disabled => $disabled]),
            $form->text('telefono2', 'Teléfono 2:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 2317-876543', $disabled => $disabled]),
        ],
        'Académico' => [
            $form->text('titulo_anterior', 'Título anterior:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: Técnico en Informática', $disabled => $disabled]),
            $form->text('becas', 'Cantidad de becas:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: 2', $disabled => $disabled]),
            $form->text('nombre_institucion_secundario', 'Nombre de institución Secundaria:', 'label-input-y-75', $alumno, ['placeholder' => 'Ej: Escuela Nacional N°1', $disabled => $disabled]),
            $form->select('titulo_secundario', 'Título secundario:', 'label-input-y-75', $alumno, [
                '' => 'Seleccione una opción',
                'No entregado',
                'Certificado de constancia de título en trámite',
                'Constancia de alumno del último año del nivel secundario',
                'Fotocopia del título original secundario',
            ], [
                $disabled => $disabled
            ]),
        ],
        'Otros' => [
            $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', $alumno, [
                'placeholder' => 'Notas o comentarios adicionales',
                $disabled => $disabled
            ]),
        ]
    ], false) ?>


    @if (in_array($admin->rol, [0,1]))
    <div class="boton-eliminar">
        @if (!$config['modo_seguro'])
        <div>
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
        </div>
        @endif
    </div>
    @endif

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
<div class="table mb-5">
    <div class="table__header">
        <h2>CARRERAS</h2>
    </div>
    <div class="matricular">
            <table class="table table-bordered table-hover mb-0 text-center">
            <thead>
                <tr>
                    <th>Nombre de la carrera</th>
                    <th class="center">Estado</th>
                    <th class="center">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carreras as $carrera)
                    <tr>
                        <td class="bold">{{ $carrera->carrera_nombre }}</td>
                        <td>
                            <form action="{{ route('admin.alumno.estadoinscripcion.post', ['alumno' => $alumno->id, 'carrera' => $carrera->carrera_id]) }}" method="POST">
                                @csrf
                                <select name="estados[{{ $carrera->carrera_id }}]" class="form-select text-center">
                                    @php
                                        Log
                                    @endphp
                                    <option value=0 {{ $carrera->estado == 0 ? 'selected' : '' }}>Cursando</option>
                                    <option value=1 {{ $carrera->estado == 1 ? 'selected' : '' }}>Egresado</option>
                                    <option value=2 {{ $carrera->estado == 2 ? 'selected' : '' }}>Desertor</option>
                                </select>
                                <button type="submit" class="btn_blue btn-sm d-inline-flex">
                                    <i class="ti ti-refresh me-2" style="font-size: 1.1em;"></i>
                                    Actualizar
                                </button>
                            </form>
                        </td>
                        <td>
                            @if (in_array($admin->rol, [0,1]))
                                <form action="{{ route('admin.alumno.rematricular', ['alumno' => $alumno->id]) }}" method="GET" class="d-inline centrar">
                                    @csrf
                                    <input type="hidden" name="carrera" value="{{ $carrera->carrera_id }}">
                                    <button type="submit" class="btn_blue btn-sm d-inline-flex">
                                        <i class="ti ti-paperclip me-2" style="font-size: 1.1em;"></i>
                                        Matricular
                                    </button>
                                </form>
                            @endif 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if (in_array($admin->rol, [0,1]))
            <div class="text-center mt-5">
                <a href="{{ route('admin.inscriptos.create', ['alumno_id' => $alumno->id]) }}" 
                class="btn_blue inline-flex items-center justify-center"
                style="width: 250px; padding: 10px 0; border-radius: 8px;">
                    <i class="ti ti-plus me-2" style="font-size: 1.3em;"></i>
                    Inscribir a otra carrera
                </a>
            </div>
        @endif
    </div>

       
{{-- CURSADAS --}}
<div class="table mb-5">
    <div class="table__header">
        <h2>Cursadas</h2>
    </div>

    <div class="accordion" id="accordionCursadas">
        @php
        $agrupadasCursadas = collect($alumno->cursadas)->groupBy(fn($c) => $c->carrera);
        @endphp

        @foreach ($agrupadasCursadas as $carrera => $porCarrera)
        @php
        // agrupamos por el año de la asignatura desde carrera_asignatura_profesor
        $porAnio = $porCarrera->groupBy('anio_asignatura')->sortKeys();
        @endphp

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCarreraCursadas{{ $loop->index }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseCarreraCursadas{{ $loop->index }}" aria-expanded="false">
                        {{ json_decode($carrera)->nombre }}
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
                                            {{-- Texto del año --}}
                                            {{ $anio !== null ? (intval($anio) + 1) . '° año' : 'Año no definido' }}

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
                                                            <td class="bold">{{ $cursada->asignatura->nombre }}</td>
                                                            <td>
                                                                <div style="display: flex; justify-content: center;">
                                                                    {{ $cursada->condicionString() }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div style="display: flex; justify-content: center;">
                                                                    {{ $cursada->aprobado() }}
                                                                </div>
                                                            </td>
                                                            <td class="flex just-center" style="min-width: 170px;">
                                                                <div style="display: flex; justify-content: center; gap:10px">
                                                                    <a href="{{ route('admin.cursadas.edit', $cursada->id) }}">
                                                                        <button class="btn_blue btn_contraible">
                                                                            <i class="ti ti-pencil" style="font-size: 1.3em;"></i>
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
            $agrupadasExamenes = collect($alumno->examenes)->groupBy(fn($e) => $e->carrera);
        @endphp

        @foreach ($agrupadasExamenes as $carrera => $porCarrera)
        @php
        // agrupamos por el año de la asignatura (de carrera_asignatura_profesor)
        $porAnio = $porCarrera
        ->groupBy('anio_asignatura')
        ->sortKeys()
        ->sortBy(fn($grupo, $anio) => $anio === null ? 999 : $anio); // los "sin año" al final
        @endphp

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCarreraExamenes{{ $loop->index }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseCarreraExamenes{{ $loop->index }}" aria-expanded="false">
                        {{ json_decode($carrera)->nombre }}
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
                                            {{-- Texto del año --}}
                                            {{ $anio !== null ? (intval($anio) + 1) . '° año' : 'Año no definido' }}
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
                                                            <td class="bold">{{ $examen->asignatura->nombre }}</td>
                                                            <td>
                                                                <div style="display: flex; justify-content: center;">
                                                                    {{ $formatoFecha->dma($examen->getFecha()) }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @if ($examen->aprobado == 3)
                                                                    AUSENTE
                                                                @elseif($examen->nota <= 0)
                                                                    <span class="bold" style="color: red">SIN NOTA</span>
                                                                @else
                                                                    <span class="bold"
                                                                        style="color: {{ $examen->nota >= 4 ? 'green' : 'red' }}">
                                                                        {{ $examen->nota }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td style="min-width: 170px;">
                                                                <div style="display: flex; justify-content: center; gap:10px;">
                                                                    <a href="{{ route('admin.examenes.edit', $examen->id) }}">
                                                                        <button class="btn_blue btn_contraible">
                                                                            <i class="ti ti-pencil" style="font-size: 1.3em;"></i>
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