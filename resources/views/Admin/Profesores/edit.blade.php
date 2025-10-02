@extends('Admin.template')

@section('content')
<div class="edit-form-container">
    <div class="perfil_one br">

        {{-- HEADER --}}
        @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR PROFESOR/A'])

        {{-- FORMULARIO --}}
        <div class="perfil__info">
            {!! 
                $form->generate(
                    route('admin.profesores.update', ['profesor' => $profesor->id]),
                    'put',
                    [
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
                            $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', old('fecha_nacimiento') ?? $profesor, [
                                'placeholder' => 'dd/mm/aaaa',
                                'default' => $profesor->fecha_nacimiento?->format('Y-m-d') ?? old('fecha_nacimiento'),
                            ]),
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
                            $form->text('formacion_academica', 'Formación académica:*', 'label-input-y-75', old('formacion_academica') ?? $profesor, [
                                'placeholder' => 'Ej: Profesorado en Matemática',
                                'maxlength' => 150,
                            ]),
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
                            '<div class="input-group">' .
                                $form->text('telefono2', 'Teléfono 2:', 'label-input-y-75', old('telefono2') ?? $profesor, [
                                    'placeholder' => 'Ej: 2317-876543',
                                    'maxlength' => 30,
                                ]) .
                                '<small class="text-muted">Ejemplo: 2317-876543</small>' .
                            '</div>',
                        ],

                        'Vinculación' => [
                            new \Illuminate\Support\HtmlString('
                                <h3 class="mb-3">🧾 Vinculaciones actuales</h3>
                                ' . (
                                    $profesor->asignaturas->isEmpty()
                                        ? '<p class="text-muted">Este profesor aún no tiene asignaturas vinculadas.</p>'
                                        : '
                                            <div class="table-responsive mb-4">
                                                <table class="table table-bordered table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Carrera</th>
                                                            <th>Asignatura</th>
                                                            <th>Año</th>
                                                            <th>Módulo</th>
                                                            <th>Carga horaria</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>' .
                                                        $profesor->asignaturas->map(function ($asignatura) {
                                                            $pivot = $asignatura->pivot;
                                                            $carrera = \App\Models\Carrera::find($pivot->id_carrera);
                                                            return '
                                                                <tr>
                                                                    <td>' . ($carrera?->nombre ?? '—') . '</td>
                                                                    <td>' . $asignatura->nombre . '</td>
                                                                    <td>' . $pivot->anio . '</td>
                                                                    <td>' . $pivot->tipo_modulo . '</td>
                                                                    <td>' . $pivot->carga_horaria . ' hs</td>
                                                                </tr>';
                                                        })->implode('') .
                                                    '</tbody>
                                                </table>
                                            </div>'
                                ) . '
                                <button type="button" class="btn btn-outline-primary mb-3" onclick="document.getElementById(\'bloqueVinculacionNueva\').style.display = \'block\'">
                                    Agregar nueva vinculación
                                </button>
                                <div id="bloqueVinculacionNueva" style="display: none;">
                                    ' . view('components.vinculacion-profesor', [
                                        'carreras' => $carreras,
                                        'profesor' => $profesor
                                    ])->render() . '
                                </div>
                            ')
                        ],

                        'Otros' => [
                            $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', old('observaciones') ?? $profesor, [
                                'placeholder' => 'Notas adicionales sobre el profesor/a',
                                'maxlength' => 150,
                            ]),
                        ],
                    ]
                )
            !!}
        </div>

        {{-- BOTÓN ELIMINAR --}}
        <div class="boton-eliminar">
            <form method="POST" id="form-eliminar-{{ $profesor->id }}" action="{{ route('admin.profesores.destroy', ['profesor' => $profesor->id]) }}">
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

        {{-- TABLA MESAS --}}
        <div class="table mt-4">
            <div class="table__header">
                <h2>Próximas mesas</h2>
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
