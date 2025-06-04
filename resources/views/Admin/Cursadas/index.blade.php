@extends('Admin.template')

@section('content')

  {{-- FILTROS --}}
  <?= $filtergen->generate('admin.cursadas.index',$filters,[
    'dropdowns' => [
        $carreraM->dropdown('filter_carrera_id', 'Carrera:','label-input-y-75',$filters,['first_items'=>['Todas'],'id'=>'carrera_select']),
        $form->select('filter_asignatura_id', 'Asignatura:', 'label-input-y-75', $filters,['Seleccione una carrera'], ['id'=>'asignatura_select']),
        $alumnoM->dropdown('filter_alumno_id', 'Alumno:','label-input-y-75',$filters,['first_items'=>['Todos'],'filter'=>'orderByApellidoNombre']),
        $form->select('filter_condicion', 'Condición: ', 'label-input-y-75', $filters, ['Cualquiera','Libre','Regular','Promoción','Equivalencia','Desertor']),
        $form->select('filter_aprobada', 'Estado: ', 'label-input-y-75', $filters, ['Cualquiera', 'Aprobada','Desaprobada','Cursando']),
    ],

    'fields' => [
        'anio_cursada' => 'Año',
    ]
]) ?>



        <div class="table">
            <div class="perfil__header-alt">
                <a href="{{route('admin.cursadas.create')}}"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar cursada</button></a>
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
                <td>{{$cursada->asignatura->nombre}}</td>

                <td>{{$cursada->alumno->apellidoNombre()}}</td>
                <td>
                    {{$cursada->aprobado()}}
                </td>
                <td class="flex just-center"><a href="{{route('admin.cursadas.edit', ['cursada' => $cursada->id])}}"><button class="btn_blue"><i class="ti ti-file-info"></i>Detalles</button></a></td>


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
