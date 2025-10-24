@extends('Admin.template')

@section('content')
<div class="table" data-name="tablaProfesores">

    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE PROFESORES'])

    <div class="perfil__header-alt">
        <a href="{{ route('admin.profesores.create') }}">
            <button class="btn_blue">
                <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>Agregar profesor
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
            ],
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
                    <p class="bold" style="text-transform: uppercase;">{{ $profesor->apellidoNombre() }}</p>
                    <p>dni: {{ $profesor->dniPuntos() }}</p>
                </td>
                <td>
                    <p class="excluir-mayusculas">
                        {{ $profesor->email ? $profesor->email : 'Sin mail registrado' }}
                    </p>
                    <p>
                        tel: {{ $profesor->telefono1 ? $profesor->telefono1 : 'Sin teléfono' }}
                    </p>
                </td>
                <td>
                    <p>{{ $profesor->ciudad }}</p>
                    <p>{{ $profesor->calle }} {{ $profesor->casa_numero ? $profesor->casa_numero : '' }}</p>
                </td>
                <td class="flex just-center" style="min-width: 170px;">
                    <div style="display: flex; justify-content: center; gap: 10px;">
                        <a href="{{ route('admin.profesores.edit', ['profesor' => $profesor->id]) }}">
                            <button class="btn_blue btn_contraible">
                                <i class="ti ti-pencil" style="font-size: 1.3em;"></i>
                                <span class="btn-text">Editar</span>
                            </button>
                        </a>
                        @if (!$config['modo_seguro'])
                        <form id="form-eliminar-{{ $profesor->id }}"
                            action="{{ route('admin.profesores.destroy', ['profesor' => $profesor->id]) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('delete')
                            <button type="button"
                                onclick="openGeneralModal(
                'form-eliminar-{{ $profesor->id }}',
                '¿Estás seguro de que querés eliminar al profesor: {{ mb_strtoupper($profesor->apellido, 'UTF-8') }} {{ mb_strtoupper($profesor->nombre, 'UTF-8') }}? \n\nESTA ACCIÓN NO SE PUEDE DESHACER.'
            )"
                                class="btn_icon-danger btn_contraible" style="background-color: red;">
                                <i class="ti ti-trash"></i>
                                <span class="btn-text">Eliminar</span>
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
    {{ $profesores->appends(request()->query())->links('Componentes.pagination') }}
</div>

{{-- SOLO PARA ESTA VISTA --}}
<style>
    .perfil__header-alt select,
    .perfil__header-alt input[type="text"] {
        height: 40px;
        /* misma altura para criterio y búsqueda */
    }
</style>
@endsection