@extends('Admin.template')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/vinculacion-profesor.css') }}">
    <div class="edit-form-container">
        <div class="perfil_one br">

            {{-- HEADER --}}
            @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR PROFESOR/A'])


            {{-- FORMULARIO --}}
            <div class="perfil__info">
                {!! $form->generate(route('admin.profesores.update', ['profesor' => $profesor->id]), 'put', [
                    'Profesor' => [
                        $form->text('nombre', 'Nombre:*', 'label-input-y-75', old('nombre') ?? $profesor, [
                            'placeholder' => 'Ej: Juan',
                            'maxlength' => 50,
                        ]),
                        $form->text('apellido', 'Apellido:*', 'label-input-y-75', old('apellido') ?? $profesor, [
                            'placeholder' => 'Ej: Pérez',
                            'maxlength' => 30,
                        ]),
                        $form->text('dni', 'DNI:*', 'label-input-y-75', old('dni') ?? $profesor, [
                            'placeholder' => 'Ej: 12345678',
                            'maxlength' => 10,
                        ]),
                        $form->date(
                            'fecha_nacimiento',
                            'Fecha de nacimiento:',
                            'label-input-y-75',
                            old('fecha_nacimiento') ?? $profesor,
                            [
                                'placeholder' => 'dd/mm/aaaa',
                                'default' => $profesor->fecha_nacimiento?->format('Y-m-d') ?? old('fecha_nacimiento'),
                            ],
                        ),
                        $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', old('estado_civil') ?? $profesor, [
                            '' => 'Seleccione...',
                            '0' => 'Soltero',
                            '1' => 'Casado',
                            '2' => 'Divorciado',
                            '3' => 'Viudo',
                            '4' => 'Cónyuge',
                            '5' => 'Otro',
                        ]),
                    ],
                    'Dirección' => [
                        $form->text('ciudad', 'Ciudad:', 'label-input-y-75', old('ciudad') ?? $profesor, [
                            'placeholder' => 'Ej: 9 de julio',
                            'maxlength' => 30,
                        ]),
                        $form->text('codigo_postal', 'Código postal:', 'label-input-y-75', old('codigo_postal') ?? $profesor, [
                            'placeholder' => 'Ej: 6500',
                            'maxlength' => 10,
                        ]),
                        $form->text('calle', 'Calle:', 'label-input-y-75', old('calle') ?? $profesor, [
                            'placeholder' => 'Ej: Av. Eva Perón',
                            'maxlength' => 30,
                        ]),
                        $form->text('casa_numero', 'Número de casa:', 'label-input-y-75', old('casa_numero') ?? $profesor, [
                            'placeholder' => 'Ej: 742',
                            'maxlength' => 4,
                        ]),
                        $form->text('dpto', 'Dpto:', 'label-input-y-75', old('dpto') ?? $profesor, [
                            'placeholder' => 'Ej: A',
                            'maxlength' => 5,
                        ]),
                        $form->text('piso', 'Piso:', 'label-input-y-75', old('piso') ?? $profesor, [
                            'placeholder' => 'Ej: 3',
                            'maxlength' => 15,
                        ]),
                    ],
                    'Académico' => [
                        $form->text(
                            'formacion_academica',
                            'Formación académica:*',
                            'label-input-y-75',
                            old('formacion_academica') ?? $profesor,
                            [
                                'placeholder' => 'Ej: Profesorado en Matemática',
                                'maxlength' => 150,
                            ],
                        ),
                        $form->text('anio_ingreso', 'Año de ingreso:*', 'label-input-y-75', old('anio_ingreso') ?? $profesor, [
                            'placeholder' => 'Ej: 2020',
                            'maxlength' => 4,
                        ]),
                    ],
                    'Contacto' => [
                        $form->text('email', 'Email:*', 'label-input-y-75', old('email') ?? $profesor, [
                            'placeholder' => 'ejemplo@dominio.com',
                            'maxlength' => 50,
                        ]),
                        $form->text('telefono1', 'Teléfono 1:*', 'label-input-y-75', old('telefono1') ?? $profesor, [
                            'placeholder' => 'Ej: 2317-876544',
                            'maxlength' => 30,
                        ]),
                        $form->text('telefono2', 'Teléfono 2:', 'label-input-y-75', old('telefono2') ?? $profesor, [
                            'placeholder' => 'Ej: 2317-876543',
                            'maxlength' => 30,
                        ]),
                    ],
                    'Otros' => [
                        $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', old('observaciones') ?? $profesor, [
                            'placeholder' => 'Notas adicionales sobre el profesor/a',
                            'maxlength' => 150,
                        ]),
                    ],
                ]) !!}
            </div>

            {{-- BOTÓN ELIMINAR --}}
            <div class="botones-derecha">
                <div class="boton-eliminar">
                    <form method="POST" id="form-eliminar-{{ $profesor->id }}"
                        action="{{ route('admin.profesores.destroy', ['profesor' => $profesor->id]) }}">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn_red_outline"
                            onclick="openGeneralModal(
                        'form-eliminar-{{ $profesor->id }}',
                        '¿Estás seguro de que querés eliminar al profesor: {{ mb_strtoupper($profesor->apellido, 'UTF-8') }} {{ mb_strtoupper($profesor->nombre, 'UTF-8') }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.'
                    )">
                            <i class="ti ti-trash" style="font-size: 1.3em;"></i> Eliminar profesor/a
                        </button>
                    </form>
                </div>
            </div>

            {{-- BLOQUE VINCULACIÓN FUERA DEL FORM --}}
            <div class="perfil_one br">
                <div class="perfil__header">
                    <h2>Vincular Asignaturas</h2>
                </div>

                <div style="margin-right: 10px;">
                    <div id="bloqueVinculacionNueva" style="display: row; margin: 20px">
                        @include('components.vinculacion-profesor', [
                            'carreras' => $carreras,
                            'profesor' => $profesor,
                        ])
                    </div>
                </div>
                <div class="botones-derecha">
                    <button type="button" class="btn_blue"
                        onclick="document.getElementById(\'bloqueVinculacionNueva\').style.display = 'block'">
                        <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                        Vincular
                    </button>
                </div>
            </div>

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
                                {{ $carrera?->nombre ?? 'Carrera desconocida' }}
                            </h4>

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
                                                            <th class="center">Año</th>
                                                            <th>Asignatura</th>
                                                            <th class="center">Carga horaria</th>
                                                            <th class="center">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($lista as $pivot)
                                                            <tr>
                                                                <td>
                                                                    <div
                                                                        style="display:flex; align-items: center; justify-content: center;">
                                                                        {{ $pivot->anio }}
                                                                    </div>
                                                                </td>
                                                                <td class="bold">
                                                                    {{ $pivot->nombre }}
                                                                </td>
                                                                <td>
                                                                    <div
                                                                        style="display:flex; align-items: center; justify-content: center;">
                                                                        {{ $pivot->carga_horaria ?? '—' }} hs
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <form method="POST"
                                                                        id="form-eliminar-{{ $profesor->id }}"
                                                                        action="{{ route('admin.profesores.destroy', ['profesor' => $profesor->id]) }}">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <div
                                                                            style="display:flex; align-items: center; justify-content: center;">
                                                                            <button type="button" class="btn_icon-danger"
                                                                                onclick="openGeneralModal(
                'form-eliminar-{{ $profesor->id }}',
                '¿Esta seguro que desea desvincular al profesor: {{ mb_strtoupper($profesor->apellido, 'UTF-8') }} {{ mb_strtoupper($profesor->nombre, 'UTF-8') }} de la asignatura: {{ $pivot->nombre }} ? \n\nESTA ACCIÓN NO SE PUEDE DESHACER.'
            )"
                                                                                style="background-color: red; margin-left: 10px;">
                                                                                <i class="ti ti-x"
                                                                                    style="font-size: 1.3em;"></i>
                                                                            </button>
                                                                        </div>
                                                                    </form>

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


            {{-- TABLA MESAS --}}
            <div class="perfil_one br">
                <div class="perfil__header">
                    <h2>PRÓXIMAS MESAS</h2>
                </div>
                <table class="table__body">
                    <thead>
                        <tr>
                            <th>Asignatura</th>
                            <th>Fecha</th>
                            <th>Rol</th>
                            <th class="center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mesas as $mesa)
                            <tr>
                                <td>{{ $mesa->asignatura->nombre }}</td>
                                <td>{{ $formatoFecha->dmhm($mesa->fecha) }}</td>
                                <td>
                                    @if ($mesa->prof_presidente == $profesor->id)
                                        Presidente
                                    @elseif ($mesa->prof_vocal_1 == $profesor->id)
                                        Vocal 1
                                    @elseif ($mesa->prof_vocal_2 == $profesor->id)
                                        Vocal 2
                                    @endif
                                </td>
                                <td class="flex just-center">
                                    <a href="{{ route('admin.mesas.edit', ['mesa' => $mesa->id]) }}">
                                        <button class="btn_blue">
                                            <i class="ti ti-file-info"></i> Detalles
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
