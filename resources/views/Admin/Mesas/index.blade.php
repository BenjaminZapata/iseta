@extends('Admin.template')

@section('content')

  {{-- FILTROS --}}
  <?= $filtergen->generate('admin.mesas.index',$filters,[
    'dropdowns' => [
        $carreraM->dropdown('filter_carrera_id', 'Carrera:','label-input-y-75',$filters,['first_items'=>['Todas'],'id'=>'carrera_select']),
        $form->select('filter_asignatura_id', 'Asignatura:', 'label-input-y-75', $filters,['Seleccione una carrera'], ['id'=>'asignatura_select']),
        $alumnoM->dropdown('filter_alumno_id', 'Alumno:','label-input-y-75',$filters,['first_items'=>['Todos'],'filter'=>'orderByApellidoNombre']),
        $form->select('filter_llamado', 'Llamado: ', 'label-input-y-75', $filters,['Cualquiera','Primer llamado','Segundo llamado']),
        $form->date('filter_from','Desde:','label-input-y-75',$filters),
        $form->date('filter_to','Hasta:','label-input-y-75',$filters),
        $profesorM->dropdown('filter_presidente','Profesor presidente:','label-input-y-75',$filters,['filter'=>'order','first_items'=>['Cualquiera']]),
        $profesorM->dropdown('filter_vocal1','Vocal 1:','label-input-y-75',$filters,['filter'=>'order','first_items'=>['Cualquiera']]),
        $profesorM->dropdown('filter_vocal2','Vocal 2:','label-input-y-75',$filters,['filter'=>'order','first_items'=>['Cualquiera']])
    ],
    'fields' => [
        'alumno' => 'Alumno',
        'carrera' => 'Carrera',
        'asignatura' => 'Asignatura',
        'profesor' => 'Presidente'
    ]
]) ?>


        <div class="table">
            <div class="perfil__header-alt">
                <a href="{{route('admin.mesas.create')}}"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar mesa</button></a>
            </div>
        <table class="table__body">
            <thead>
                <tr>

                    <th>Materia</th>
                    <th>Llamado</th>
                    <th>Carrera</th>
                    <th class="center">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mesas as $mesa)
                    <tr>

                    <td>

                        <p>{{$mesa->asignatura->nombre}}</p>
                    </td>
                    <td class="w-25p">
                        <p>
                            @if ($mesa->llamado == 1 || $mesa->llamado == 0)
                            Primero
                            @else
                            Segundo
                            @endif
                        </p>
                        <p>{{$formatoFecha->dmahm($mesa->fecha)}}</p>
                    </td>
                    <td>
                        <p>{{$mesa->asignatura->carrera->first()?->nombre}}</p>

                        <p>Año: {{$mesa->asignatura->anio}}</p>
                    </td>
                    <td><a href="{{route('admin.mesas.edit', ['mesa' => $mesa->id])}}"><button class="btn_blue"><i class="ti ti-file-info"></i>Detalles</button></a></td>

                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <div class="w-1/2 mx-auto p-5 pagination">
            {{ $mesas->appends(request()->query())->links('Componentes.pagination') }}
        </div>

        <script src="{{asset('js/obtener-materias.js')}}"></script>
@endsection
