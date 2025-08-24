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
                <i class="ti ti-circle-plus"></i>Agregar asignatura
            </button>
        </a>
        {{-- FILTROS --}}
        <?= $filtergen->generate('admin.asignaturas.index', $filters, [
            'dropdowns' => [
                $carreraM->dropdown('filter_carrera_id', 'Carrera:', 'label-input-y-100', $filters, ['first_items' => ['Todas'], 'id' => 'carrera_select']),
              $form->select(
    'filter_asignatura_id',
    'Asignatura:',
    'label-input-y-100',
    $filters,
    $asignaturasList->pluck('nombre', 'id')->prepend('Todas', 0)->toArray()
),



                $form->select('filter_anio', 'Año:', 'label-input-y-100', $filters, ['Todos', '1er Año', '2do Año', '3er Año', '4to Año', '5to Año']),
               $form->select('filter_carga_horaria', 'Carga Horaria:', 'label-input-y-100', $filters, [
    'Cualquiera' => 'Cualquiera',
    'Menos de 10 hs' => 'Menos de 10 hs',
    '10 a 20 hs' => '10 a 20 hs',
    'Más de 20 hs' => 'Más de 20 hs',
])

            ],
            'fields' => [
                'nombre' => 'Nombre',
                'carrera' => 'Carrera',
                'anio' => 'Año',
                'carga_horaria' => 'Carga Horaria',
            ],
        ]) ?>
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
