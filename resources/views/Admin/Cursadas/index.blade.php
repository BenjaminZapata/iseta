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

<div class="table" data-name="tablaCursadas">

    {{-- HEADER AVATAR --}}

    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE CURSADAS'])  

    <div class="perfil__header-alt">
        <a href="{{route('admin.cursadas.create')}}"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar cursada</button></a>
        {{-- FILTROS --}}
        <?= $filtergen->generate('admin.cursadas.index', $filters, [
            'dropdowns' => [
                $carreraM->dropdown('filter_carrera_id', 'Carrera:', 'label-input-y-100', $filters, ['first_items' => ['Todas'], 'id' => 'carrera_select']),
                $form->select('filter_asignatura_id', 'Asignatura:', 'label-input-y-100', $filters, ['Seleccione una carrera'], ['id' => 'asignatura_select']),
                $alumnoM->dropdown('filter_alumno_id', 'Alumno:', 'label-input-y-100', $filters, ['first_items' => ['Todos'], 'filter' => 'orderByApellidoNombre']),
                $form->select('filter_condicion', 'Condición: ', 'label-input-y-100', $filters, ['Cualquiera', 'Libre', 'Regular', 'Promoción', 'Equivalencia', 'Desertor']),
                $form->select('filter_aprobada', 'Estado: ', 'label-input-y-100', $filters, ['Cualquiera', 'Aprobada', 'Desaprobada', 'Cursando']),
            ],

            'fields' => [
                'anio_cursada' => 'Año',
            ]
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
                <td class="bold">{{$cursada->asignatura->nombre}}</td>

                <td>{{$cursada->alumno->apellidoNombre()}}</td>
                <td>
                    {{$cursada->aprobado()}}
                </td>
                <td class="flex just-center"><a href="{{route('admin.cursadas.edit', ['cursada' => $cursada->id])}}"><button class="btn_blue"><i class="ti ti-file-info" style="font-size: 1.3em; margin-right: 8px;"></i>Modificar</button></a></td>


            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="w-1/2 mx-auto p-5 pagination">
    {{ $cursadas->appends(request()->query())->links('Componentes.pagination') }}
</div>
<script src="{{asset('js/obtener-materias.js')}}"></script>
@endsection