@extends('Admin.template')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Admin/Cursadas/cursadas.css') }}">

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

                $alumnoM->dropdown('filter_alumno_id', 'Alumno:', 'label-input-y-100', old('filter_alumno_id', $filters->filter_alumno_id ?? null), [
                    'first_items' => ['Todos'],
                    'filter' => 'orderByApellidoNombre',
                ]),

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
            @php
            // Agrupamos las cursadas por carrera y año (sin modificar la colección original)
            Log::debug(print_r($cursadas['summary'], true));
            $agrupadas = $cursadas['summary']->groupBy(fn($c) => $c->id_carrera . '-' . $c->anio_cursada);
            @endphp

            @foreach ($agrupadas as $key => $grupo)
            @php
            $primera = $grupo->first();
            $idCarreraAnio = $primera->id_carrera . '-' . $primera->anio_cursada;
            @endphp

            <!-- NIVEL 1: Carrera -->
            <tr>
                <td><strong>{{ $primera->carrera->nombre ?? 'Sin carrera' }}</strong></td>
                <td class="center">{{ $primera->anio_cursada }}</td>
                <td class="center">
                    <div class="centrar">
                        <button class="btn_blue career-summary" data-target="#careerBody{{ $idCarreraAnio }}">
                            <i class="ti ti-folder iconos"></i> Ver asignaturas
                        </button>
                    </div>

                </td>
            </tr>

            <!-- Contenedor de asignaturas -->
            <tr class="career-body hidden" id="careerBody{{ $idCarreraAnio }}">
                <td colspan="4">
                    <table class="inner-table">
                        <tbody>
                            @foreach ($grupo as $cursada)
                            @php
                            $groupId =
                            $cursada->id_carrera .
                            '-' .
                            $cursada->id_asignatura .
                            '-' .
                            $cursada->anio_cursada;
                            $cursadas_ungrp =
                            $cursadas['allCursadas'][$cursada->id_carrera][$cursada->id_asignatura][
                            $cursada->anio_cursada
                            ] ?? collect();
                            @endphp

                            <!-- NIVEL 2: Asignatura -->
                            <tr class="subject-summary">
                                <td style="padding-left: 40px;">
                                    {{ $cursada->asignatura->nombre ?? 'Sin asignatura' }}
                                </td>
                                <td class="flex just-center" style="min-width: 200px">
                                    <div class="centrar" style="gap: 10px">
                                        <div>
                                            <button class="btn_blue subject-toggle"
                                                data-target="#subjectBody{{ $groupId }}">
                                                <i class="ti ti-users iconos"></i> Ver alumnos
                                            </button>
                                        </div>
                                        <a href="{{ route('admin.cursadas.registroAcademico', ['cursada_group' => $groupId]) }}"
                                            class="btn_blue" onclick="event.stopPropagation();">
                                            <i class="ti ti-file-export iconos"></i>
                                            Registro de Avance
                                        </a>
                                    </div>

                                </td>
                            </tr>

                            <!-- NIVEL 3: Alumnos -->
                            <tr class="subject-body hidden" id="subjectBody{{ $groupId }}">
                                <td colspan="4">
                                    <table class="inner-table">
                                        <thead>
                                            <tr>
                                                <th>ALUMNO</th>
                                                <th class="center">ESTADO</th>
                                                <th class="center">CONDICIÓN</th>
                                                <th class="center">ACCIÓN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cursadas_ungrp as $sub_cursada)
                                            <tr>
                                                <td class="bold">
                                                    {{ $sub_cursada->alumno->apellidoNombre() ?? 'Sin alumno' }}
                                                </td>
                                                <td class="center">{{ $sub_cursada->aprobado() }}</td>
                                                <td class="center">{{ $sub_cursada->condicionString() }}
                                                </td>
                                                <td class="flex just-center" style="min-width: 170px;">
                                                    <div class="centrar" style=" gap: 10px;">
                                                        <a
                                                            href="{{ route('admin.cursadas.edit', ['cursada' => $sub_cursada->id]) }}">
                                                            <button class="btn_blue btn_contraible">
                                                                <i class="ti ti-pencil"
                                                                    style="font-size: 1.3em;"></i>
                                                                <span class="btn-text">Editar</span>
                                                            </button>
                                                        </a>

                                                        @if (!$config['modo_seguro'])
                                                        <form id="form-eliminar-{{ $sub_cursada->id }}"
                                                            action="{{ route('admin.cursadas.destroy', $sub_cursada->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                onclick="openGeneralModal('form-eliminar-{{ $sub_cursada->id }}',
                                                                    '¿Estás seguro de que querés eliminar la cursada de la asignatura:  {{ strtoupper($cursada->asignatura->nombre ?? 'Sin Asignatura') }} de la carrera {{ strtoupper($cursada->carrera->nombre ?? 'Sin Carrera') }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                                                class="btn_icon-danger btn_contraible"
                                                                style="background-color: red;">
                                                                <i class="ti ti-trash"
                                                                    style="font-size: 1.3em"></i>
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
    {{ $cursadas['summary']->links('Componentes.pagination') }}
</div>
<script src="{{ asset('js/obtener-materias.js') }}"></script>
<script>
    document.addEventListener('click', function(e) {
        // === NIVEL 1: Botón "Ver asignaturas" ===
        const careerBtn = e.target.closest('.career-summary');
        if (careerBtn) {
            const targetSelector = careerBtn.dataset.target;
            const target = document.querySelector(targetSelector);

            if (target) {
                // Cerrar TODAS las demás carreras abiertas
                document.querySelectorAll('.career-body').forEach(body => {
                    if (body !== target && !body.classList.contains('hidden')) {
                        body.classList.add('hidden');
                        const otherBtn = document.querySelector(`.career-summary[data-target="#${body.id}"]`);
                        if (otherBtn) {
                            otherBtn.innerHTML = `<i class="ti ti-folder iconos"></i> Ver asignaturas`;
                        }
                    }
                });

                // Cerrar todos los alumnos abiertos
                document.querySelectorAll('.subject-body:not(.hidden)').forEach(sub => {
                    sub.classList.add('hidden');
                    const subBtn = document.querySelector(`.subject-toggle[data-target="#${sub.id}"]`);
                    if (subBtn) {
                        subBtn.innerHTML = `<i class="ti ti-users iconos"></i> Ver alumnos`;
                    }
                });

                // Alternar carrera actual
                target.classList.toggle('hidden');
                if (target.classList.contains('hidden')) {
                    careerBtn.innerHTML = `<i class="ti ti-folder iconos"></i> Ver asignaturas`;
                } else {
                    careerBtn.innerHTML = `<i class="ti ti-folder-open iconos"></i> Ocultar asignaturas`;
                }
            }
            return;
        }

        // === NIVEL 2: Botón "Ver alumnos" ===
        const subjectBtn = e.target.closest('.subject-toggle');
        if (subjectBtn) {
            e.stopPropagation();

            const targetSelector = subjectBtn.dataset.target;
            const target = document.querySelector(targetSelector);

            if (target) {
                // Cerrar otros niveles 3 abiertos dentro de la misma carrera
                const parentCareerBody = subjectBtn.closest('.career-body');
                if (parentCareerBody) {
                    parentCareerBody.querySelectorAll('.subject-body:not(.hidden)').forEach(row => {
                        if (row !== target) {
                            row.classList.add('hidden');
                            const otherBtn = parentCareerBody.querySelector(`.subject-toggle[data-target="#${row.id}"]`);
                            if (otherBtn) {
                                otherBtn.innerHTML = `<i class="ti ti-users iconos"></i> Ver alumnos`;
                            }
                        }
                    });
                }

                // Alternar actual
                target.classList.toggle('hidden');
                if (target.classList.contains('hidden')) {
                    subjectBtn.innerHTML = `<i class="ti ti-users iconos"></i> Ver alumnos`;
                } else {
                    subjectBtn.innerHTML = `<i class="ti ti-user-off iconos"></i> Ocultar alumnos`;
                }
            }
            return;
        }
    });
</script>
@endsection