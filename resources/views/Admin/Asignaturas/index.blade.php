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

    <div class="table" data-name="tablaAsignatura">
        @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE ASIGNATURAS'])

        {{-- BOTÓN CREAR Y FILTROS --}}
        <div class="perfil__header-alt">
            <a href="{{ route('admin.asignaturas.create') }}">
                <button class="btn_blue">
                    <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>Agregar asignatura
                </button>
            </a>
        </div>

        {{-- TABLA DE ASIGNATURAS --}}
        <table class="table__body">
            <thead>
                <tr>
                    <th>Asignatura</th>
                    <th>Carrera</th>
                    <th class="center">Año</th>
                    <th class="center">Carga horaria</th>
                    <th class="center">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asignaturas as $asignatura)
                    <tr>
                        <td class="bold">{{ $asignatura->nombre }}</td>
                        <td>
                            @foreach ($asignatura->carrera as $carrera)
                                {{ $carrera->nombre }}<br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($asignatura->carrera as $carrera)
                                <div style="display: flex; justify-content: center;">
                                    {{ $carrera->pivot->anioStr() }}<br>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <div style="display: flex; justify-content: center;">
                                {{ $asignatura->carga_horaria }} hs
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; justify-content: center;">

                                @if (!$config['modo_seguro'])
                                    <form id="form-eliminar-{{ $asignatura->id }}"
                                        action="{{ route('admin.asignaturas.destroy', $asignatura->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="openGeneralModal('form-eliminar-{{ $asignatura->id }}',
                                    '¿Estás seguro de que querés eliminar a la asignatura: {{ strtoupper($asignatura->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                            class="btn_icon-danger" style="background-color: red; margin-left: 10px;">
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
        {{ $asignaturas->appends(request()->query())->links('Componentes.pagination') }}
    </div>
@endsection
