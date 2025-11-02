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

<div class="table" data-name="tablaMesas">

    {{-- HEADER AVATAR --}}

    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE MESAS'])

    <div class="perfil__header-alt">
        <a href="{{ route('admin.mesas.create') }}">
            <button class="btn_blue">
                <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>Agregar mesa
            </button>
        </a>
        {{-- FILTROS --}}
        <?= $filtergen->generate('admin.mesas.index', $filters, [
            'dropdowns' => [
                $carreraM->dropdown('filter_carrera_id', 'Carrera:', 'label-input-y-100', $filters, ['first_items' => ['Todas'], 'id' => 'carrera_select']),
                $form->select('filter_asignatura_id', 'Asignatura:', 'label-input-y-100', $filters, ['Seleccione una carrera'], ['id' => 'asignatura_select']),
                $profesorM->dropdown('filter_presidente', 'Presidente:', 'label-input-y-100', $filters, ['filter' => 'order', 'first_items' => ['Cualquiera']]),
                $alumnoM->dropdown('filter_alumno_id', 'Alumno:', 'label-input-y-100', $filters, ['first_items' => ['Todos'], 'filter' => 'orderByApellidoNombre']),
                $form->date('filter_from', 'Desde:', 'label-input-y-100', $filters),
                $form->date('filter_to', 'Hasta:', 'label-input-y-100', $filters),
 ],
            'fields' => [
                'carrera' => 'Carrera',
                'asignatura' => 'Asignatura',
                'profesor' => 'Presidente',
                'alumno' => 'Alumno',
            ],
        ]) ?>
    </div>
    <table class="table__body">
        <thead>
            <tr>
                <th>Carrera y Asignatura</th>
                <th>fecha y Llamado</th>
                <th>Profesores</th>
                <th class="center">Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mesas as $mesa)
            <tr>
                <td>
                    <p class="bold">{{ $mesa->asignatura->carrera->first()->nombre ?? 'Sin carrera asignada' }}</p>
                    <p>{{ $mesa->asignatura->nombre }}</p>
                </td>
                <td>
                    <p>
                        @if ($mesa->llamado == 1 || $mesa->llamado == 0)
                        Primer Llamado
                        @else
                        Segundo Llamado
                        @endif
                    </p>
                    <p>{{ $formatoFecha->dmahm($mesa->fecha) }}</p>
                </td>
                <td>
                    <p><span class="bold">Presidente:</span> {{ $mesa->profesor?->apellidoNombre() ?? 'No asignado' }}</p>
                    <p><span class="bold">Vocal 1:</span> {{ $mesa->vocal1?->apellidoNombre() ?? 'No asignado' }}</p>
                    <p><span class="bold">Vocal 2:</span> {{ $mesa->vocal2?->apellidoNombre() ?? 'No asignado' }}</p>
                </td>
                <td class="center" style="min-width: 180px;">
                    <div style="display: flex; justify-content: center; gap:10px;">
                        <a href="{{ route('admin.mesas.edit', ['mesa' => $mesa->id]) }}">
                            <button class="btn_blue btn_contraible">
                                <i class="ti ti-pencil"
                                    style="font-size: 1.3em;"></i>
                                <span class="btn-text">Editar</span>
                            </button>
                        </a>
                        @if (!$config['modo_seguro'])
                        <div>
                            <form id="form-eliminar-{{ $mesa->id }}"
                                action="{{ route('admin.mesas.destroy', $mesa->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="btn_icon-danger btn_contraible" style="background-color: red;"
                                    onclick="openGeneralModal(
                                    'form-eliminar-{{ $mesa->id }}',
                                    '¿Estás seguro de que querés eliminar la mesa?\n\n' +
                                    'Carrera: {{ $mesa->asignatura->carrera->first()->nombre ?? "No asignada" }}\n' +
                                    'Asignatura: {{ $mesa->asignatura?->nombre ?? "No asignada" }}\n' +
                                    'Fecha: {{ $mesa->fecha ? \Carbon\Carbon::parse($mesa->fecha)->format("d/m/Y") : "No definida" }}\n' +
                                    'Presidente: {{ $mesa->profesor?->apellidoNombre() ?? "No asignado" }}\n' +
                                    'Vocal 1: {{ $mesa->vocal1?->apellidoNombre() ?? "No asignado" }}\n' +
                                    'Vocal 2: {{ $mesa->vocal2?->apellidoNombre() ?? "No asignado" }}\n\n' +
                                    'ESTA ACCIÓN NO SE PUEDE DESHACER.')">
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
</div>

{{-- PAGINACIÓN --}}
<div class="w-full flex justify-center p-5 pagination">
    {{ $mesas->appends(request()->query())->links('Componentes.pagination') }}
</div>

<script src="{{ asset('js/obtener-materias.js') }}"></script>
@endsection