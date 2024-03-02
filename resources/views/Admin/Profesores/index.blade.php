@extends('Admin.template')

@section('content')

{{-- FILTROS --}}
<?= $filtergen->generate('admin.profesores.index',$filtros, [
    'order' => [
        'nombre'=> 'Nombre',
        'dni' => 'Dni',
        'dni-desc' => 'Dni descendiente'
    ],
    'show' => [
        'ninguno' => 'Ninguno',
        'registrados' => 'Registrados'
    ],
    'searchField' => [
        'placeholder' => 'Nombre, apellido, email,  ...'
    ]

]) ?>

        
        <div class="table">
            <div class="perfil__header-alt">
                <a href="{{route('admin.profesores.create')}}"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar profesor</button></a>
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
                        <p>{{$profesor->apellidoNombre()}}</p>
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
                    <td class="flex just-center"><a  href="{{route('admin.profesores.edit', ['profesor' => $profesor->id])}}"><button class="btn_blue"><i class="ti ti-file-info"></i>Detalles</button></a></td>
      
    
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div class="w-1/2 mx-auto p-5 pagination">
            {{ $profesores->appends(request()->query())->links('Componentes.pagination') }}
        </div>
  
@endsection
