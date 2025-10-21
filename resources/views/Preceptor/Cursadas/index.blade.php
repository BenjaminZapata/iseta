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

    <div class="table" data-name="tablaCursadas">

        {{-- HEADER AVATAR --}}

        @include('preceptor.header-avatar', ['tituloSeccion' => 'GESTIÓN DE CURSADAS'])

        <div class="perfil__header-alt">
            <a href="{{ route('preceptor.cursadas.create') }}"><button class="btn_blue"><i class="ti ti-circle-plus"
                        style="font-size: 1.3em; margin-right: 8px;"></i>Agregar
                    cursada</button></a>
            {{-- FILTROS --}}
            <?= $filtergen->generate('preceptor.cursadas.index', $filters, [
                    'dropdowns' => [
                        $carreraM->dropdown('filter_carrera_id', 'Carrera:', 'label-input-y-100', old('filter_carrera_id', $filters->filter_carrera_id ?? null), [
                            'first_items' => ['Todas'],
                            'id' => 'carrera_select',
                        ]),

                        $form->select('filter_asignatura_id', 'Asignatura:', 'label-input-y-100', old('filter_asignatura_id', $filters->filter_asignatura_id ?? null), ['Seleccione una asignatura'], ['id' => 'asignatura_select']),

             $alumnoM->dropdown(
    'filter_alumno_id',
    'Alumno:',
    'label-input-y-100',
    old('filter_alumno_id', $filters->filter_alumno_id ?? null),
    [
        'first_items' => ['Todos'],
        'filter' => 'orderByApellidoNombre',
    ]
),

                        $form->select('filter_condicion', 'Condición:', 'label-input-y-100', old('filter_condicion', $filters->filter_condicion ?? null), ['Cualquiera', 'Libre', 'Regular', 'Promoción', 'Equivalencia', 'Desertor']),

                        $form->select('filter_aprobada', 'Estado:', 'label-input-y-100', old('filter_aprobada', $filters->filter_aprobada ?? null), ['Cualquiera', 'Aprobada', 'Desaprobada', 'Cursando']),
                    ],

                    'fields' => [
                        'anio_cursada' => 'Año',
                    ],
                ]) ?>
        </div>
        <table class="table__body">
            <thead>
                <tr>
                    <th>Materia</td>
                    <th>Alumno/a</td>
                    <th>Estado</td>
                    <th class="center">Acción</th>
                </tr>
            </thead>

            <tbody>
                {{-- @dd($cursadas) --}}
                @foreach ($cursadas as $cursada)
                    <tr>
                        <td class="bold">{{ $cursada->asignatura?->nombre ?? 'Sin asignatura' }}</td>


                        <td>
                            {{ $cursada->alumno?->apellidoNombre() ?? 'Sin alumno asignado' }}
                        </td>
                        <td>
                            {{ $cursada->aprobado() }}
                        </td>
                        <td class="flex just-center">
                            <div style="display: flex; justify-content: center;">
                                <a href="{{ route('admin.cursadas.edit', ['cursada' => $cursada->id]) }}">
                                    <button class="btn_blue"><i class="ti ti-file-info"
                                            style="font-size: 1.3em; margin-right: 8px;"></i>Modificar</button>
                                </a>
                               @if (!$config['modo_seguro'])
                                    <div>
                                        <form id="form-eliminar-{{ $cursada->id }}"
                                            action="{{ route('preceptor.cursadas.destroy', $cursada->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="openGeneralModal('form-eliminar-{{ $cursada->id }}',
                                    '¿Estás seguro de que querés eliminar la cursada de la asignatura:  {{ strtoupper($cursada->asignatura->nombre ?? 'sin asignatura') }} de la carrera {{ strtoupper($cursada->carrera->nombre ?? 'sin carrera') }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                                class="btn_icon-danger" style="background-color: red; margin-left: 10px;">
                                                <i class="ti ti-trash" style="font-size: 1.3em"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="w-full flex justify-center p-5 pagination">
        {{ $cursadas->appends(request()->query())->links('Componentes.pagination') }}
    </div>
    <script src="{{ asset('js/obtener-materias.js') }}"></script>
@endsection