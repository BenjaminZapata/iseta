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

<div class="table" data-name="tablaInscriptos">

    {{-- HEADER AVATAR --}}

    @include('preceptor.header-avatar', ['tituloSeccion' => 'GESTIÓN DE INSCRIPTOS'])

    <div class="perfil__header-alt">
        <a href="{{ route('preceptor.inscriptos.create') }}"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar

                inscripcion</button></a>
        {{-- FILTROS --}}
        <?= $filtergen->generate('preceptor.inscriptos.index', $filters, [
            'dropdowns' => [
                $carreraM->dropdown(
                    'filter_carrera_id',
                    'Carrera:',
                    'label-input-y-100',
                    old('filter_carrera_id', $filters->filter_carrera_id ?? null),
                    [
                        'first_items' => ['Todas'],
                        'id' => 'carrera_select'
                    ]
                ),

                $form->select(
                    'filter_vigente',
                    'Estado Carreras:',
                    'label-input-y-100',
                    old('filter_vigente', $filters->filter_vigente ?? null),
                    ['Todas', 'No Vigentes', 'Vigentes']
                ),

                $alumnoM->dropdown(
                    'filter_alumno_id',
                    'Alumno:',
                    'label-input-y-100',
                    old('filter_alumno_id', $filters->filter_alumno_id ?? null),
                    [
                        'first_items' => ['Todos'],
                        'filter' => 'orderByApellidoNombre'
                    ]
                ),

                $form->select(
                    'filter_estado',
                    'Estado:',
                    'label-input-y-100',
                    old('filter_estado', $filters->filter_estado ?? null),
                    ['Cursando', 'Egresado', 'Desertor']
                ),

                $form->select(
                    'filter_ciudad',
                    'Ciudad:',
                    'label-input-y-100',
                    old('filter_ciudad', $filters->filter_ciudad ?? null),
                     ['' => 'Cualquiera'] + $alumnoM->ciudades()
                ),
            ],

            'fields' => [
                'anio_inscripcion'  => 'Año de inscripción',
                'anio_finalizacion' => 'Año de finalización',
            ],
        ]) ?>
    </div>
    <table class="table__body">
        <thead>
            <tr>
                <th>Apellido y nombre</th>
                {{-- <th>Dni</th> --}}
                <th>Carrera</th>
                <th>Estado</th>
                <th>Periodo</th>
                <th class="center">Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inscripciones as $inscripcion)
            <tr>
                <td class="bold">
                    {{ $inscripcion->alumno->apellidoNombre() }}
                </td>

                {{-- <td>{{$alumno->dni}}</td> --}}
                <td>{{ $inscripcion->carrera->nombre }}</td>
                <td>
                    {{ $inscripcion->estado() }}
                </td>
                <td>
                    {{ $inscripcion->anio_inscripcion ? $inscripcion->anio_inscripcion : 'Sin datos' }}
                    -
                    {{ $inscripcion->anio_finalizacion ? $inscripcion->anio_finalizacion : 'Presente' }}
                </td>
                <td class="flex just-center">
                    <div style="display: flex; justify-content: center;">
                        <a href="{{ route('preceptor.inscriptos.edit', ['inscripto' => $inscripcion->id]) }}">
                            <button class="btn_blue"><i class="ti ti-file-info"
                                    style="font-size: 1.3em; margin-right: 8px;"></i>Modificar</button>
                        </a>
                        <!-- Boton eliminar -->
                       @if (!$config['modo_seguro'])
                            <form method="POST"
                             id="form-eliminar-{{ $inscripcion->id }}"
                            action="{{ route('preceptor.inscriptos.destroy', $inscripcion->id) }}"
                            class="form-eliminar"
                            style="margin-left: 10px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                            onclick="openGeneralModal(
                            'form-eliminar-{{ $inscripcion->id }}',
                            '¿Estás seguro de que querés eliminar al inscripto: {{ strtoupper($inscripcion->alumno->apellido) }} {{ strtoupper($inscripcion->alumno->nombre) }}? \n\nESTA ACCIÓN NO SE PUEDE DESHACER.')"
                            class="btn_icon-danger"
                            style="background-color: red; margin-left: 10px;">
                            <i class="ti ti-trash" style="font-size: 1.3em;"></i>
                            </button>
                            </form>
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
    {{ $inscripciones->appends(request()->query())->links('Componentes.pagination') }}
</div>
@endsection
