@extends('preceptor.template')

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
        <a href="{{ route('preceptor.mesas.create') }}">
            <button class="btn_blue">
                <i class="ti ti-circle-plus"></i>Agregar mesa
            </button>
        </a>
        {{-- FILTROS --}}
        <?= $filtergen->generate('preceptor.mesas.index', $filters, [
            'dropdowns' => [
                $carreraM->dropdown('filter_carrera_id', 'Carrera:', 'label-input-y-100', $filters, ['first_items' => ['Todas'], 'id' => 'carrera_select']),
                $form->select('filter_asignatura_id', 'Asignatura:', 'label-input-y-100', $filters, ['Seleccione una carrera'], ['id' => 'asignatura_select']),
                $alumnoM->dropdown('filter_alumno_id', 'Alumno:', 'label-input-y-100', $filters, ['first_items' => ['Todos'], 'filter' => 'orderByApellidoNombre']),
                $form->select('filter_llamado', 'Llamado: ', 'label-input-y-100', $filters, ['Cualquiera', 'Primer llamado', 'Segundo llamado']),
                $form->date('filter_from', 'Desde:', 'label-input-y-100', $filters),
                $form->date('filter_to', 'Hasta:', 'label-input-y-100', $filters),
                $profesorM->dropdown('filter_presidente', 'Presidente:', 'label-input-y-100', $filters, ['filter' => 'order', 'first_items' => ['Cualquiera']]),
                $profesorM->dropdown('filter_vocal1', 'Vocal 1:', 'label-input-y-100', $filters, ['filter' => 'order', 'first_items' => ['Cualquiera']]),
                $profesorM->dropdown('filter_vocal2', 'Vocal 2:', 'label-input-y-100', $filters, ['filter' => 'order', 'first_items' => ['Cualquiera']]),
            ],
            'fields' => [
                'alumno' => 'Alumno',
                'carrera' => 'Carrera',
                'asignatura' => 'Asignatura',
                'profesor' => 'Presidente',
            ],
        ]) ?>
    </div>
    <table class="table__body">
        <thead>
            <tr>

                <th>Materia</th>
                <th>Llamado</th>
                <th>Año</th>
                <th class="center">Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mesas as $mesa)
            <tr>
                <td>
                    <p class="bold">{{ $mesa->asignatura->nombre }}</p>
                </td>
                <td class="w-25p">
                    <p>
                        @if ($mesa->llamado == 1 || $mesa->llamado == 0)
                        Primero
                        @else
                        Segundo
                        @endif
                    </p>
                    <p>{{ $formatoFecha->dmahm($mesa->fecha) }}</p>
                </td>
                <td>
                    <p>{{ $mesa->asignatura->carreraDirecta?->nombre }}</p>
                    <p>{{ $mesa->asignatura->anio }}° año</p>
                </td>
                <td class="center">
                    <div style="display: flex; justify-content: center;">
                        <a href="{{ route('preceptor.mesas.edit', ['mesa' => $mesa->id]) }}">
                            <button class="btn_blue"><i class="ti ti-file-info"
                                    style="font-size: 1.3em; margin-right: 8px;"></i>Modificar</button>
                        </a>
                        @if (!$config['modo_seguro'])
                        <div>
                            <form method="POST" class="form-eliminar"
                                action="{{ route('preceptor.mesas.destroy', ['mesa' => $mesa->id]) }}"
                                style="margin-left: 10px;">
                                @csrf
                                @method('delete')
                                <button class="btn_icon-danger" style="background-color: red"
                                    onclick="openGeneralModal('form-eliminar-{{ $mesa->id }}', '¿Estás seguro de que querés eliminar la mesa: {{ strtoupper($mesa->asignatura->nombre)  }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                    class="btn_icon-danger" style="background-color: red; margin-left: 10px;">
                                    <i class="ti ti-trash" style="font-size: 1.3em;"></i>
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