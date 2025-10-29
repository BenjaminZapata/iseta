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
    <table class="table">
        <thead>
            <tr>
                <th>Materia</td>
                <th>Carrera</td>
                <th>Año</td>
                <th class="center">Acción</th>
            </tr>
        </thead>
        {{-- @dd($cursadas) --}}
        @php
            $cursadas_group = $cursadas->groupBy(['id_carrera', 'id_asignatura', 'anio_cursada']);
            $anio_index = 0;
        @endphp
        <tbody>
            @foreach ($cursadas_group as $carreras)
                @foreach ($carreras as $asignaturas)
                    @foreach ($asignaturas as $anio => $cursadas_ungrp)
                        @php $anio_index++; @endphp
                        <tr>
                            <td>{{ $cursadas_ungrp[0]->asignatura?->nombre ?? 'Sin asignatura' }}</td>
                            <td>{{ $cursadas_ungrp[0]->carrera?->nombre ?? 'Sin carrera' }}</td>
                            <td>{{ $cursadas_ungrp[0]->anio_cursada }}</td>
                        </tr>
                        <div class="accordion-item mb-2">
                            <h2 class="accordion-header" id="headingAnio{{ $anio_index }}">
                                <button class="accordion-button collapsed font-500" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseAnio{{ $anio_index }}"
                                    aria-expanded="false" aria-controls="collapseAnio{{ $anio_index }}">

                                </button>
                            </h2>

                            <div id="collapseAnio{{ $anio_index }}" class="accordion-collapse collapse"
                                aria-labelledby="headingAnio{{ $anio_index }}"
                                data-bs-parent="#asignaturasAccordion">
                                <div class="accordion-body p-0">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Alumno</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cursadas_ungrp as $cursada)
                                                <tr>
                                                    <td>{{ $cursada->alumno->apellidoNombre() ?? 'Sin alumno asignado' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
                    {{-- {{ $cursada->alumno?->apellidoNombre() ?? 'Sin alumno asignado' }}
                </td>
                <td>
                    {{ $cursada->aprobado() }}
                </td>
                <td class="flex just-center" style="min-width: 170px; ">
                    <div style="display: flex; justify-content: center; gap: 10px;">
                        <a href="{{ route('admin.cursadas.edit', ['cursada' => $cursada->id]) }}">
                            <button class="btn_blue btn_contraible">
                                <i class="ti ti-pencil"
                                    style="font-size: 1.3em;"></i>
                                <span class="btn-text">Editar</span>
                            </button>
                        </a>
                        @if (!$config['modo_seguro'])
                        <div>
                            <form id="form-eliminar-{{ $cursada->id }}"
                                action="{{ route('admin.cursadas.destroy', $cursada->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="openGeneralModal('form-eliminar-{{ $cursada->id }}',
                                    '¿Estás seguro de que querés eliminar la cursada de la asignatura:  {{ strtoupper($cursada->asignatura->nombre ?? 'sin asignatura') }} de la carrera {{ strtoupper($cursada->carrera->nombre ?? 'sin carrera') }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
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
</div> --}}
</div>

{{-- PAGINACIÓN --}}
<div class="w-full flex justify-center p-5 pagination">
    {{ $cursadas->appends(request()->query())->links('Componentes.pagination') }}
</div>
<script src="{{ asset('js/obtener-materias.js') }}">
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.toggle-group');
        if (!btn) return;
        const target = document.querySelector(btn.dataset.target);
        if (!target) return;
        target.style.display = (target.style.display === 'none' || getComputedStyle(target).display === 'none') ? '' : 'none';
    });
</script>
@endsection