@extends('Admin.template')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.asignaturas.create') }}">
            <button class="btn_blue">
                <i class="ti ti-circle-plus"></i> Agregar asignatura</button>
        </a>

        <div class="flex gap-4">
            <form action="{{ route('admin.asignaturas.index') }}" method="GET" class="flex items-center gap-2">
                <input name="filtro" type="text" placeholder="Buscar..." class="form-input">
                <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Filtros</button>
            </form>

            <a href="{{ route('admin.asignaturas.index') }}">
                <button class="btn btn-primary"><i class="ti ti-filter-off"></i> Eliminar filtros</button>
            </a>
        </div>
    </div>

    <div class="table">
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
                        <td>
                            <a href="{{ route('admin.asignaturas.edit', $asignatura->id) }}">
                                <button class="btn btn-secondary"><i class="ti ti-edit"></i> Editar</button>
                            </a>
                
                         <form id="form-eliminar-{{ $asignatura->id }}"
                        action="{{ route('admin.asignaturas.destroy', $asignatura->id) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            onclick="openGeneralModal('form-eliminar-{{ $asignatura->id }}', 
                            '¿Estás seguro de que querés eliminar a la asignatura: {{ strtoupper($asignatura-> nombre)}}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
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
        {{ $asignaturas->appends(request()->query())->links('Componentes.pagination') }}
    </div>
@endsection