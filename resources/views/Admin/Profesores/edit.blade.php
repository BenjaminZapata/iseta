@extends('Admin.template')

@section('content')
    <div class="edit-form-container">
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR PROFESOR/A'])
            <nav aria-label="breadcrumb" class="mb-4">
                <ul class="breadcrumb flex items-center gap-2 text-sm text-gray-700">
                    <li class="flex items-center">
                        <a href="/admin/profesores">Profesores</a>
                    </li>
                    <li>
                        <a href="/admin/profesores/{{ $profesor->id }}/edit">{{ $profesor->apellido }}
                            {{ $profesor->nombre }}</a>
                    </li>
                </ul>
            </nav>
            <div class="perfil__info">

                <?= $form->generate(route('admin.profesores.update', ['profesor' => $profesor->id]), 'put', [
                        'Profesor' => [$form->text('dni', 'DNI:', 'label-input-y-75', $profesor), $form->text('nombre', 'Nombre:', 'label-input-y-75', $profesor), $form->text('apellido', 'Apellido:', 'label-input-y-75', $profesor), $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', $profesor, ['default' => $profesor->fecha_nacimiento->format('Y-m-d')]), $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', $profesor, ['Soltero', 'Casado', 'Divorciado', 'Viudo', 'Conyuge', 'Otro'])],
                        'Dirección' => [$form->text('ciudad', 'Ciudad:', 'label-input-y-75', $profesor), $form->text('codigo_postal', 'Codigo postal:', 'label-input-y-75', $profesor), $form->text('calle', 'Calle:', 'label-input-y-75', $profesor), $form->text('casa_numero', 'Altura:', 'label-input-y-75', $profesor), $form->text('departamento', 'Departamento:', 'label-input-y-75', $profesor), $form->text('piso', 'Piso:', 'label-input-y-75', $profesor)],
                        'Academico' => [$form->text('formacion_academica', 'Formacion academica:', 'label-input-y-75', $profesor), $form->text('anio_ingreso', 'Año de ingreso:', 'label-input-y-75', $profesor)],
                        'Contacto' => [$form->text('email', 'Email:', 'label-input-y-75', $profesor), $form->text('telefono1', 'Telefono 1:', 'label-input-y-75', $profesor), $form->text('telefono2', 'Telefono 2:', 'label-input-y-75', $profesor), $form->text('telefono3', 'Telefono 3:', 'label-input-y-75', $profesor)],
                        'Otros' => [$form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', $profesor)],
                    ]) ?>
                <div class="boton-eliminar">

                    <div>
                        <!-- Formulario de eliminación -->
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
