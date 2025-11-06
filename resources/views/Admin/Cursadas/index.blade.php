@extends('Admin.template')

@section('content')
<style>
    #filters .label-input-y-100 label {
        text-align: left !important;
        display: block !important;
        width: 100%;
        padding-top: 15px;
    }

    /* Fila clickeable */
    .group-summary {
        cursor: pointer;
    }

    /* Inicialmente oculto */
    .hidden {
        display: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/Admin/cursadas.css') }}">

<div class="table" data-name="tablaCursadas">

    {{-- HEADER AVATAR --}}

    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE CURSADAS'])

    <div class="perfil__header-alt">
        <a href="{{ route('admin.cursadas.create') }}"><button class="btn_blue"><i class="ti ti-circle-plus"
                    style="font-size: 1.3em; margin-right: 8px;"></i>Agregar
                cursada</button></a>
        {{-- FILTROS --}}
        <?= $filtergen->generate('admin.cursadas.index', $filters, [
            'dropdowns' => [
                $carreraM->dropdown('filter_carrera_id', 'Carrera:', 'label-input-y-100', old('filter_carrera_id', $filters->filter_carrera_id ?? null), [
                    'first_items' => ['Todas'],
                    'id' => 'carrera_select',
                ]),

                $form->select('filter_asignatura_id', 'Asignatura:', 'label-input-y-100', old('filter_asignatura_id', $filters->filter_asignatura_id ?? null), ['Seleccione una asignatura'], ['id' => 'asignatura_select']),

                $alumnoM->dropdown(
                    'filter_alumno_id',
                    'Alumno:',
                    'label-input-y-100',
                    old('filter_alumno_id', $filters->filter_alumno_id ?? null),
                    [
                        'first_items' => ['Todos'],
                        'filter' => 'orderByApellidoNombre',
                    ]
                ),

                $form->select('filter_condicion', 'Condición:', 'label-input-y-100', old('filter_condicion', $filters->filter_condicion ?? null), ['Cualquiera', 'Libre', 'Regular', 'Promoción', 'Equivalencia', 'Desertor']),

                $form->select('filter_aprobada', 'Estado:', 'label-input-y-100', old('filter_aprobada', $filters->filter_aprobada ?? null), ['Cualquiera', 'Aprobada', 'Desaprobada', 'Cursando']),
            ],

            'fields' => [
                'anio_cursada' => 'Año',
            ],
        ]) ?>
    </div>
    {{-- @dd($cursadas) --}}

    <table class="table">
        <thead>
            <tr>
                <th>CURSADA</th>
                <th class="center">AÑO</th>
                <th class="center">ACCIÓN</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cursadas['summary'] as $cursada)
            @php
            $groupId = $cursada->id_carrera.'-'.$cursada->id_asignatura.'-'.$cursada->anio_cursada;
            $cursadas_ungrp = $cursadas['allCursadas'][$cursada->id_carrera][$cursada->id_asignatura][$cursada->anio_cursada] ?? collect();
            @endphp

            <!-- Fila resumen -->
            <tr class="group-summary" data-target="#groupBody{{ $groupId }}">
                <td>
                    <strong>
                        {{ $cursada->carrera->nombre ?? 'Sin carrera' }}
                    </strong>
                    <br>
                    {{ $cursada->asignatura->nombre ?? 'Sin asignatura' }}
                </td>
                <td>
                    <div class="centrar">
                        {{ $cursada->anio_cursada }}
                    </div>
                </td>
                <td>
                    <div class="centrar">
                        <a href="{{ route('admin.cursadas.registroAcademico', ["cursada_group" => $groupId]) }}"
                            class="btn_blue"
                            onclick="event.stopPropagation();">
                            <i class="ti ti-file-export" style="font-size: 1.3em; margin-right: 8px;"></i>
                            Registro de Avance
                        </a>
                    </div>
                </td>
            </tr>

            <!-- Fila colapsable -->
            <tr class="group-body-row hidden" id="groupBody{{ $groupId }}">
                <td colspan="4">
                    <table class="inner-table">
                        <thead>
                            <tr>
                                <th>ALUMNO</th>
                                <th class="center">ESTADO</th>
                                <th class="center">CONDICION</th>
                                <th class="center" style="min-width: 200px;">ACCION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cursadas_ungrp as $sub_cursada)
                            <tr>
                                <td class="bold">{{ $sub_cursada->alumno->apellidoNombre() ?? 'Sin alumno' }}</td>
                                <td>
                                    <div class="centrar">{{ $sub_cursada->aprobado() }}</div>
                                </td>
                                <td>
                                    <div class="centrar">{{ $sub_cursada->condicionString() }}</div>
                                </td>
                                <td style="min-width: 200px;">
                                    <div style="display: flex; justify-content: center; gap: 10px;">
                                        <a href="{{ route('admin.cursadas.edit', ['cursada' => $sub_cursada->id]) }}">
                                            <button class="btn_blue btn_contraible">
                                                <i class="ti ti-pencil"
                                                    style="font-size: 1.3em;"></i>
                                                <span class="btn-text">Editar</span>
                                            </button>
                                        </a>
                                        @if (!$config['modo_seguro'])
                                        <div>
                                            <form id="form-eliminar-{{ $sub_cursada->id }}"
                                                action="{{ route('admin.cursadas.destroy', $sub_cursada->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    onclick="openGeneralModal('form-eliminar-{{ $sub_cursada->id }}',
                                                            '¿Estás seguro de que querés eliminar la cursada de la asignatura:  {{ strtoupper($cursada->asignatura->nombre ?? 'Sin Asignatura') }} de la carrera {{ strtoupper($cursada->carrera->nombre ?? 'Sin Carrera') }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                                    class="btn_icon-danger btn_contraible" style="background-color: red;">
                                                    <i class="ti ti-trash" style="font-size: 1.3em"></i>
                                                    <span class="btn-text">Eliminar</span>
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- PAGINACIÓN --}}
<div class="w-full flex justify-center p-5 pagination">
    {{ $cursadas['summary']->appends(request()->query())->links('Componentes.pagination') }}
</div>
<script src="{{ asset('js/obtener-materias.js') }}"></script>
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.group-summary');
        if (!row) return;

        const targetSelector = row.dataset.target;
        const target = document.querySelector(targetSelector);
        if (!target) return;

        target.classList.toggle('hidden');
    });
</script>
@endsection