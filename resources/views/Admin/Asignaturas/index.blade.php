@extends('Admin.template')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('Regente.asignaturas.create') }}">
            <button class="btn_blue">
                <i class="ti ti-circle-plus"></i> Agregar asignatura</button>
        </a>

        <div class="flex gap-4">
            <form action="{{ route('Regente.asignaturas.index') }}" method="GET" class="flex items-center gap-2">
                <input name="filtro" type="text" placeholder="Buscar..." class="form-input">
                <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Filtros</button>
            </form>

            <a href="{{ route('Regente.asignaturas.index') }}">
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
                            <a href="{{ route('Regente.asignaturas.edit', $asignatura->id) }}">
                                <button class="btn btn-secondary"><i class="ti ti-edit"></i> Editar</button>
                            </a>
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