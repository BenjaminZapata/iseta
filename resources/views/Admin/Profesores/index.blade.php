@extends('Admin.template')

@section('content')


<div class="table" data-name="tablaProfesores">

    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE PROFESORES'])

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
                    <p class="bold" style="text-transform: uppercase;">{{$profesor->apellidoNombre()}}</p>
                    <p>dni: {{$profesor->dniPuntos()}}</p>
                </td>
                <td>
                    <p class="excluir-mayusculas">
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
                <td class="flex just-center">
                    <div>
                        <a href="{{route('admin.profesores.edit', ['profesor' => $profesor->id])}}">
                            <button class="btn_blue">
                                <i class="ti ti-file-info" style="font-size: 1.3em; margin-right: 8px;"></i>
                                Modificar
                            </button>
                        </a>
                    </div>
                    <form id="form-eliminar-{{ $profesor->id }}" action="{{ route('admin.profesores.destroy', $profesor -> id) }}"
                        method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            onclick="openGeneralModal('form-eliminar-{{ $profesor->id }}', '¿Estás seguro de que querés eliminar al profesor: {{ strtoupper($profesor->apellido) }} {{ strtoupper($profesor->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
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
    {{ $profesores->appends(request()->query())->links('Componentes.pagination') }}
</div>

@endsection