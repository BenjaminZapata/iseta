@extends('Admin.template')

@section('content')

{{-- FILTROS --}}
<?= $filtergen->generate('admin.inscriptos.index',$filters,[
    'dropdowns' => [
        $carreraM->dropdown('filter_carrera_id', 'Carrera:','label-input-y-75',$filters,['first_items'=>['Todas'],'id'=>'carrera_select']),
        $form->select('filter_vigente','Carreras vigentes: ', 'label-input-y-75',$filters,['Todas','No Vigentes','Vigentes']),
        $alumnoM->dropdown('filter_alumno_id', 'Alumno:','label-input-y-75',$filters,['first_items'=>['Todos'],'filter'=>'orderByApellidoNombre']),
        $form->select('filter_estado','Estado: ', 'label-input-y-75',$filters,['Cualquiera','Cursando','Egresado','Desertor']),
        $form->select('filter_ciudad', 'Ciudad:','label-input-y-75',$filters,$alumnoM->ciudades()),
        


    ],

    'fields' => [
        'anio_inscripcion' => 'Año de inscripción',
        'anio_finalizacion' => 'Año de finalización',
    ]
]) ?>



        <div class="table">
            <div class="perfil__header-alt">
                <a href="{{route('admin.inscriptos.create')}}"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar inscripcion</button></a>
            </div>
        <table class="table__body">
            <thead>
                <tr>
                    <th>Apellido y nombre</th>
                    {{-- <th>Dni</th> --}}
                    <th>Carrera</th>
                    <th>Estado</th>
                    <th class="center">Acción</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($inscripciones as $inscripcion)
            <tr>
                <td>
                    {{$inscripcion->alumno->apellidoNombre()}}
                </td>
                
                {{-- <td>{{$alumno->dni}}</td> --}}
                <td>{{$inscripcion->carrera->nombre}}</td>
                <td>
    {{ $inscripcion->estado() }}
</td>

                <td class="flex just-center"><a href="{{route('admin.inscriptos.edit', ['inscripto' => $inscripcion->id])}}"><button class="btn_blue"><i class="ti ti-file-info"></i>Detalles</button></a></td>
                
            </tr>
            @endforeach
            </tbody>
            
            

        </table>
        </div>
        

        
        
        <div class="w-1/2 mx-auto p-5 pagination">
            {{ $inscripciones->appends(request()->query())->links('Componentes.pagination') }}
        </div>    
@endsection
