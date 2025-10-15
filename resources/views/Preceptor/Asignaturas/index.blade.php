@extends('precptor.template')

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
    @include('preceptor.header-avatar', ['tituloSeccion' => 'GESTIÓN DE ASIGNATURAS'])

    {{-- BOTÓN CREAR Y FILTROS --}}
    <div class="perfil__header-alt">
        <a href="{{ route('preceptor.asignaturas.create') }}">
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
               <div style="display: flex; justify-content: center;">
    {{ $asignatura->anioStr($asignatura->carrera->first()->id ?? 0) }}
</div>

                </td>
                <td>
                    <div style="display:flex; align-items: center; justify-content: center;"">
                        {{ $asignatura->carga_horaria }} hs
                    </div>
                </td>
                <td>
                    <div style=" display:flex; align-items: center; justify-content: center;">
                        <div style="display:flex; align-items: center; justify-content: center;">
                            @if (!$config['modo_seguro'])
                            <form id="form-eliminar-{{ $asignatura->id }}"
                                action="{{ route('preceptor.asignaturas.destroy', $asignatura->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="openGeneralModal('form-eliminar-{{ $asignatura->id }}',
                                `¿Estás seguro de que querés eliminar la asignatura?\n\n
        Nombre: {{ strtoupper($asignatura->nombre) }}\n
        {{ isset($asignatura->cantidad_modulo) && $asignatura->cantidad_modulo ? 'Módulos: ' . $asignatura->cantidad_modulo : 'Carga horaria: ' . $asignatura->carga_horaria }}\n
         Año: {{ $asignatura->anio }}\n\n
         ESTA ACCIÓN NO SE PUEDE DESHACER.`)"
                                    class="btn_icon-danger" style="background-color: red;">
                                    <i class="ti ti-trash" style="font-size: 1.3em;"></i>
                                </button>
                            </form>
                            @endif
                        </div>
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