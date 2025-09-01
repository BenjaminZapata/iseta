@extends('preceptor.template')

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
            <a href="{{ route('preceptor.alumnos.create') }}">
                <button class="btn_blue">
                    <i class="ti ti-circle-plus"></i>Agregar alumno</button>
            </a>
            {{-- FILTROS --}}
            <?= $filtergen->generate('preceptor.alumnos.index', $filters, [
        'dropdowns' => [
            $carreraM->dropdown('filter_carrera_id', 'Carrera:', 'label-input-y-100', $filters, ['first_items' => ['Todas']]),
            $form->select('filter_ciudad', 'Ciudad:', 'label-input-y-100', $filters, $alumnoM->ciudades()),
            $form->select( 'filter_titulo',
    'Estado del título:',
    'label-input-y-100',
    $filters->filter_titulo ?? 0,
    [
        0 => 'Todos',
        1 => 'Fotocopia del título original secundario',
        2 => 'Certificado de constancia de título en trámite',
        3 => 'Constancia de alumno del último año del nivel secundario',
        4 => 'No entregado',
    ])],

        'fields' => [
            'alumno' => 'Alumno',
            'dni' => 'Dni',
            'ciudad' => 'Ciudad',
            'titulo_secundario' => 'Titulo' 
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
                            <a href="{{ route('preceptor.alumnos.edit', ['alumno' => $alumno->id]) }}">
                                <button class="btn_blue"><i class="ti ti-file-info"
                                        style="font-size: 1.3em; margin-right: 8px;"></i>Modificar</button>
                            </a>
                            <form id="form-eliminar-{{ $alumno->id }}"
                                action="{{ route('preceptor.alumnos.destroy', $alumno->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="openGeneralModal('form-eliminar-{{ $alumno->id }}', '¿Estás seguro de que querés eliminar al alumno {{ $alumno->nombre }}? Esta acción no se puede deshacer.')"
                                    class="btn_icon-danger" style="background-color: red; margin-left: 10px;">
                                    <i class="ti ti-trash" style="font-size: 1.3em;"></i>
                                </button>
                            </form>
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