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

{{-- TABLA --}}
<div class="table" data-name="tablaCarreras">

    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE ASIGNATURAS']) 

    <div class="perfil__header-alt" style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{route('admin.asignaturas.create')}}">
        <button class="btn_blue">
            <i class="ti ti-circle-plus"></i> Agregar asignatura
        </button>
    </a>
    {{-- FILTROS --}}
    
    <button class="btn_blue">
            <i class="ti ti-search"></i> Filtrar
        </button>

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