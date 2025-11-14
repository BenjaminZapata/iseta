@extends('Admin.template')
@php
$admin = Auth::guard('admin')->user();
@endphp
@section('content')
<link rel="stylesheet" href="{{ asset('css/Admin/Cursadas/cursadas.css') }}">

<div class="table" data-name="tablaCursadas">

    {{-- HEADER AVATAR --}}

    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE CURSADAS'])

    <div class="perfil__header-alt">
        @if (in_array($admin->rol, [0,1]))
        <a href="{{ route('admin.cursadas.create') }}"><button class="btn_blue"><i class="ti ti-circle-plus"
                    style="font-size: 1.3em; margin-right: 8px;"></i>Agregar cursada</button>
        </a>
        @endif
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
        @php
        // Agrupamos las cursadas por carrera y año (sin modificar la colección original)
        $agrupadas = $cursadas->groupBy(fn($c) => $c->id_carrera . '-' . $c->anio_cursada);
        @endphp

        @foreach ($agrupadas as $key => $grupo)
        @php
        $primera = $grupo->first();
        $idCarreraAnio = $primera->id_carrera . '-' . $primera->anio_cursada;
        @endphp

        <livewire:arbol-cursadas :grupo="$grupo" :idCarreraAnio="$idCarreraAnio" :primera="$primera" :key="$key" />
        @endforeach


    </table>
</div>

{{-- PAGINACIÓN --}}
<div class="w-full flex justify-center p-5 pagination">
    {{ $cursadas->links('Componentes.pagination') }}
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