@extends('Admin.template')

@section('content')


<div class="table">
    <div class="perfil__header-alt">
        <a href="{{route('admin.profesores.create')}}">
            <button class="btn_blue">
                <i class="ti ti-circle-plus"></i>Agregar profesor
            </button>
        </a>
        {{-- FILTROS --}}
        <?= $filtergen->generate('admin.profesores.index', $filters, [
            // 'dropdowns' => [
            //     $carreraM->dropdown('filter_carrera_id','Carrera:', 'label-input-y-100',$filters, ['first_items' => ['Todas']])
            // ],
            'fields' => [
                'profesor' => 'Profesor',
                'dni' => 'Dni',
                'email' => 'Email',
                'ciudad' => 'Ciudad',
                'telefono1' => 'Telefono'
            ]
        ]) ?>
    </div>
    <table class="table__body">
        <thead>
            <tr>
                <th>Profesor</th>
                <th>Contacto</th>
                <th>Dirección</th>
                <th class="center">Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($profesores as $profesor)
            <tr>
                <td>
                    <p class="bold">{{$profesor->apellidoNombre()}}</p>
                    <p>dni: {{$profesor->dniPuntos()}}</p>
                </td>
                <td>
                    <p>
                        {{$profesor->email?$profesor->email:'Sin mail registrado'}}
                    </p>
                    <p>
                        tel: {{$profesor->telefono1?$profesor->telefono1:'Sin teléfono'}}
                    </p>
                </td>
                <td>
                    <p>{{$profesor->ciudad}}</p>
                    <p>{{$profesor->calle}} {{$profesor->casa_numero?$profesor->casa_numero:''}}</p>
                </td>
                <td class="flex just-center"><a href="{{route('admin.profesores.edit', ['profesor' => $profesor->id])}}"><button class="btn_blue"><i class="ti ti-file-info"></i>Detalles</button></a></td>


            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="w-1/2 mx-auto p-5 pagination">
    {{ $profesores->appends(request()->query())->links('Componentes.pagination') }}
</div>

@endsection