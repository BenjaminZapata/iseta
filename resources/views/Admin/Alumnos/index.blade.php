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


    {{-- CONTENT --}}

    <div class="table" data-name="tablaAlumnos">

        @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE ALUMNOS'])

        {{-- BOTON CREAR --}}

        <div class="perfil__header-alt" style="display: flex; align-items: center; gap: 1rem;">
            <a href="{{ route('admin.alumnos.create') }}">
                <button class="btn_blue">
                    <i class="ti ti-circle-plus"></i>Agregar alumno</button>
            </a>
            {{-- FILTROS --}}
            <?= $filtergen->generate('admin.alumnos.index', $filters, [
        'dropdowns' => [
            $carreraM->dropdown('filter_carrera_id', 'Carrera:', 'label-input-y-100', $filters, ['first_items' => ['Todas']]),
            $form->select('filter_ciudad', 'Ciudad:', 'label-input-y-100', $filters, $alumnoM->ciudades()),
            $form->select('filter_estado_civil', 'Estado civil:', 'label-input-y-100', $filters, ['Todos', 'Soltero', 'Casado', 'Divorciado', 'Viudo', 'Conyuge', 'Otro']),
            $form->select('filter_titulo', 'Titulo Secundario:', 'label-input-y-100', $filters, ['Todos', 'No Entregado', 'Constancia de título en trámite', 'Constancia ultimo año del nivel secundario', 'Titulo Secundario'])
        ],
        'fields' => [
            'alumno' => 'Alumno',
            'dni' => 'Dni',
            'email' => 'Email',
            'ciudad' => 'Ciudad',
            'telefono1' => 'Telefono',
        ],
    ]) ?>
        </div>

        {{-- TABLA --}}
        <table class="table__body">

            {{-- HEADER --}}
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Contacto</th>
                    <th>Dirección</th>
                    <th class="center">Acción</th>
                </tr>
            </thead>


            {{-- TBODY --}}
            <tbody>
                @foreach ($alumnos as $alumno)
                    <tr>
                        <td class="capitalize">
                            <p class="bold" style="text-transform: uppercase;">{{ $alumno->apellidoNombre() }}</p>
                            <p>dni: {{ $alumno->dniPuntos() }}</p>
                        </td>

                        <td>
                            <p style="text-transform: none;">{{ $alumno->email ? $alumno->email : 'Sin mail registrado' }}
                            </p>
                            @if ($alumno->telefono1)
                                <p>tel: {{ $alumno->telefono1 }}</p>
                            @elseif ($alumno->telefono2)
                                <p>tel: {{ $alumno->telefono2 }}</p>
                            @elseif ($alumno->telefono3)
                                <p>tel: {{ $alumno->telefono3 }}</p>
                            @else
                                <p>tel: Sin telefono</p>
                            @endif
                        </td>
                        <td>
                            <p>{{ $alumno->ciudad }}</p>
                            <p>{{ $alumno->calle }} {{ $alumno->casa_numero ? $alumno->casa_numero : '' }}</p>
                        </td>
                        <td class="flex just-center">
                            <a href="{{ route('admin.alumnos.edit', ['alumno' => $alumno->id]) }}">
                                <button class="btn_blue"><i class="ti ti-file-info"
                                        style="font-size: 1.3em; margin-right: 8px;"></i>Modificar</button>
                            </a>
                            <a href="{{ route('admin.alumnos.edit', ['alumno' => $alumno->id]) }} " style="margin-left: 10px;">
                                <button class="btn_icon-danger" style="background-color: red"><i class="ti ti-trash"
                                        style="font-size: 1.3em;"></i></button>
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