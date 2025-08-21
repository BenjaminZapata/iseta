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
    <div class="perfil__header-alt" style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('admin.asignaturas.create') }}">
            <button class="btn_blue">
                <i class="ti ti-circle-plus"></i> Agregar asignatura
            </button>
        </a>

        <div class="flex gap-4">
            <form action="{{ route('admin.asignaturas.index') }}" method="GET" class="flex items-center gap-2">
                <input name="filtro" type="text" placeholder="Buscar..." class="form-input">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-filter"></i> Filtros
                </button>
            </form>

            <a href="{{ route('admin.asignaturas.index') }}">
                <button class="btn btn-primary">
                    <i class="ti ti-filter-off"></i> Eliminar filtros
                </button>
            </a>
        </div>
    </div>

    {{-- TABLA DE ASIGNATURAS --}}
    <table class="table__body">
        <thead>
            <tr>
                <th>Asignatura</th>
                <th>Carrera</th>
                <th>Año</th>
                <th>Carga horaria</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asignaturas as $asignatura)
                <tr>
                    <td>{{ $asignatura->nombre }}</td>
                    <td>
                        @foreach ($asignatura->carrera as $carrera)
                            {{ $carrera->nombre }}<br>
                        @endforeach
                    </td>
                    <td>{{ $asignatura->anioStr() }}</td>
                    <td>{{ $asignatura->carga_horaria }} hs</td>
                    <td class="flex just-center">
                        <a href="{{ route('admin.asignaturas.edit', $asignatura->id) }}">
                            <button class="btn_blue">
                                <i class="ti ti-file-info" style="font-size: 1.3em; margin-right: 8px;"></i>
                                Modificar
                            </button>
                        </a>

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
