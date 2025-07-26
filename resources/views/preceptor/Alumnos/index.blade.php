@extends('Admin.template')

@section('content')
    <style>
        #filters .label-input-y-100 label {
            text-align: left !important;
            display: block !important;
            width: 100%;
            padding-top: 15px;
        }
    </style>

    <div class="table" data-name="tablaAlumnos">
        @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE ALUMNOS'])

        <div class="perfil__header-alt" style="display: flex; align-items: center; gap: 1rem;">
            <a href="{{ route('preceptor.alumnos.create') }}">
                <button class="btn_blue">
                    <i class="ti ti-circle-plus"></i>Agregar alumno
                </button>
            </a>

            <?= $filter->generate('preceptor.alumnos.index', $filters, [
        'dropdowns' => [
            $carreraM->dropdown('filter_carrera_id', 'Carrera:', 'label-input-y-100', $filters, ['first_items' => ['Todas']]),
            $form->select('filter_ciudad', 'Ciudad:', 'label-input-y-100', $filters, $alumnoM->ciudades()),
            $form->select('filter_estado_civil', 'Estado civil:', 'label-input-y-100', $filters, ['Todos', 'Soltero', 'Casado', 'Divorciado', 'Viudo', 'Conyuge', 'Otro'])
        ],
        'fields' => [
            'alumno' => 'Alumno',
            'dni' => 'Dni',
            'email' => 'Email',
            'ciudad' => 'Ciudad',
            'telefono1' => 'Teléfono'
        ]
    ]) ?>
        </div>

        <table class="table__body">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Contacto</th>
                    <th>Dirección</th>
                    <th class="center">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alumnos as $alumno)
                    <tr>
                        <td class="capitalize">
                            <p class="bold" style="text-transform: uppercase;">{{ $alumno->apellidoNombre() }}</p>
                            <p>DNI: {{ $alumno->dniPuntos() }}</p>
                        </td>
                        <td>
                            <p style="text-transform: none;">{{ $alumno->email ?? 'Sin mail registrado' }}</p>
                            @if ($alumno->telefono1)
                                <p>Tel: {{ $alumno->telefono1 }}</p>
                            @elseif ($alumno->telefono2)
                                <p>Tel: {{ $alumno->telefono2 }}</p>
                            @elseif ($alumno->telefono3)
                                <p>Tel: {{ $alumno->telefono3 }}</p>
                            @else
                                <p>Tel: Sin teléfono</p>
                            @endif
                        </td>
                        <td>
                            <p>{{ $alumno->ciudad }}</p>
                            <p>{{ $alumno->calle }} {{ $alumno->casa_numero ?? '' }}</p>
                        </td>
                        <td class="flex just-center">
                            <a href="{{ route('preceptor.preceptor.alumnos.edit', ['alumno' => $alumno->id]) }}">
                                <button class="btn_blue">
                                    <i class="ti ti-file-info" style="font-size: 1.3em; margin-right: 8px;"></i>Modificar
                                </button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="w-1/2 mx-auto p-5 pagination">
        {{ $alumnos->appends(request()->query())->links('Componentes.pagination') }}
    </div>
@endsection