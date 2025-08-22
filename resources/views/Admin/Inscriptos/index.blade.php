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

<div class="table" data-name="tablaInscriptos">

    {{-- HEADER AVATAR --}}

    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE INSCRIPTOS'])

    <div class="perfil__header-alt">
        <a href="{{ route('admin.inscriptos.create') }}"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar

                inscripcion</button></a>
        {{-- FILTROS --}}
        <?= $filtergen->generate('admin.inscriptos.index', $filters, [
            'dropdowns' => [$carreraM->dropdown('filter_carrera_id', 'Carrera:', 'label-input-y-100', $filters, ['first_items' => ['Todas'], 'id' => 'carrera_select']), $form->select('filter_vigente', 'Estado Carreras: ', 'label-input-y-100', $filters, ['Todas', 'No Vigentes', 'Vigentes']), $alumnoM->dropdown('filter_alumno_id', 'Alumno:', 'label-input-y-100', $filters, ['first_items' => ['Todos'], 'filter' => 'orderByApellidoNombre']), $form->select('filter_estado', 'Estado: ', 'label-input-y-100', $filters, ['Cursando', 'Egresado', 'Desertor']), $form->select('filter_ciudad', 'Ciudad:', 'label-input-y-100', $filters, $alumnoM->ciudades())],

            'fields' => [
                'anio_inscripcion' => 'Año de inscripción',
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
                        <a href="{{ route('admin.inscriptos.edit', ['inscripto' => $inscripcion->id]) }}">
                            <button class="btn_blue"><i class="ti ti-file-info"
                                    style="font-size: 1.3em; margin-right: 8px;"></i>Modificar</button>
                        </a>
                        {{-- Boton eliminar 
                        @if (!$config['modo_seguro'])
                        <div>
                            <form method="POST" class="form-eliminar"
                                action="{{ route('admin.inscriptos.destroy', ['inscripto' => $inscripcion->id]) }}"
                        style="margin-left: 10px;">
                        @csrf
                        @method('delete')
                        <button class="btn_icon-danger" style="background-color: red"
                            onclick="openGeneralModal('form-eliminar-{{ $inscripcion->id }}', '¿Estás seguro de que querés eliminar al inscripto: {{ strtoupper($inscripcion->alumno->apellido) }} {{ strtoupper($inscripcion->alumno->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                            class="btn_icon-danger" style="background-color: red; margin-left: 10px;">
                            <i class="ti ti-trash" style="font-size: 1.3em;"></i>
                        </button>
                        </form>
                    </div>
                    @endif
                    --}}
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