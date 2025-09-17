@extends('Admin.template')

@section('content')
<div class="edit-form-container">
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR PROFESOR/A'])
        <div class="perfil__info">
            <?= $form->generate(route('admin.profesores.update', ['profesor' => $profesor->id]), 'put', [
                'Profesor' => [
                    $form->text('dni', 'DNI:', 'label-input-y-75', old('dni') ?? $profesor),
                    $form->text('nombre', 'Nombre:', 'label-input-y-75', old('nombre') ?? $profesor),
                    $form->text('apellido', 'Apellido:', 'label-input-y-75', old('apellido') ?? $profesor),
                    $form->text('lugar_nacimiento', 'Lugar de nacimiento:', 'label-input-y-75', old('lugar_nacimiento') ?? $profesor),
                    $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', old('fecha_nacimiento') ?? $profesor, [
                        'default' => $profesor->fecha_nacimiento?->format('Y-m-d') ?? old('fecha_nacimiento')
                    ]),
                    $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', old('estado_civil') ?? $profesor, [
                        'Soltero', 'Casado', 'Divorciado', 'Viudo', 'Conyuge', 'Otro'
                    ])
                ],
                'Dirección' => [
                    $form->text('ciudad', 'Ciudad:', 'label-input-y-75', old('ciudad') ?? $profesor),
                    $form->text('codigo_postal', 'Codigo postal:', 'label-input-y-75', old('codigo_postal') ?? $profesor),
                    $form->text('calle', 'Calle:', 'label-input-y-75', old('calle') ?? $profesor),
                    $form->text('casa_numero', 'Altura:', 'label-input-y-75', old('casa_numero') ?? $profesor),
                    $form->text('dpto', 'Departamento:', 'label-input-y-75', old('dpto') ?? $profesor),
                    $form->text('piso', 'Piso:', 'label-input-y-75', old('piso') ?? $profesor)
                ],
                'Academico' => [
                    $form->text('formacion_academica', 'Formacion academica:', 'label-input-y-75', old('formacion_academica') ?? $profesor),
                    $form->text('anio_ingreso', 'Año de ingreso:', 'label-input-y-75', old('anio_ingreso') ?? $profesor)
                ],
                'Contacto' => [
                    $form->text('email', 'Email:', 'label-input-y-75', old('email') ?? $profesor),
                    $form->text('telefono_1', 'Telefono 1:', 'label-input-y-75', old('telefono_1') ?? $profesor),
                    '<div class="input-group">' .
                        $form->text('telefono_2', 'Telefono 2:', 'label-input-y-75', old('telefono_2') ?? $profesor, [
                            'placeholder' => '+54-11-12345678'
                        ]) .
                        '<small class="text-muted">Ejemplo: +54-11-12345678</small>' .
                    '</div>',
                    '<div class="input-group">' .
                        $form->text('telefono_3', 'Telefono 3:', 'label-input-y-75', old('telefono_3') ?? $profesor, [
                            'placeholder' => '+54-11-87654321'
                        ]) .
                        '<small class="text-muted">Ejemplo: +54-11-87654321</small>' .
                    '</div>'
                ],
                'Otros' => [
                    $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', old('observaciones') ?? $profesor)
                ],
            ]) ?>
            <div class="boton-eliminar">

                <div>
                   <!-- Formulario de eliminación -->
                    <form method="POST" id="form-eliminar-{{ $profesor->id }}"
                    action="{{ route('admin.profesores.destroy', ['profesor' => $profesor->id]) }}">
                     @csrf
                     @method('delete')
                    <button type="button"
                    class="btn_red_outline"
                    onclick="openGeneralModal(
                    'form-eliminar-{{ $profesor->id }}',
                    '¿Estás seguro de que querés eliminar al profesor: {{ mb_strtoupper($profesor->apellido, 'UTF-8') }} {{ mb_strtoupper($profesor->nombre, 'UTF-8') }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.'
                    )">
                    <i class="ti ti-trash" style="font-size: 1.3em;"></i> Eliminar profesor/a
                    </button>
                    </form>
                </div>
            </div>


            <div class="table">
                <div class="table__header">
                    <h2>Proximas mesas</h2>
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
                            <td class="flex just-center"><a
                                    href="{{ route('admin.mesas.edit', ['mesa' => $mesa->id]) }}"><button
                                        class="btn_blue"><i class="ti ti-file-info"></i>Detalles</button></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
@endsection
