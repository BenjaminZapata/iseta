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

    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE CARRERAS'])

    <div class="perfil__header-alt">
        <a href="{{ route('admin.carreras.create') }}">
            <button class="btn_blue">
                <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>Agregar carrera
            </button>
        </a>

        {{-- FILTROS --}}
        <?= $filtergen->generate('admin.carreras.index', $filters, [
            'dropdowns' => [
                $form->select(
                    'filter_vigente',
                    'Condición:',
                    'label-input-y-100',
                    $filters->filter_vigente ?? '',
                    [
                        '' => 'Seleccione una opción',
                        '1' => 'Vigentes',
                        '0' => 'No vigentes',
                    ]

                ),



                $form->select(
                    'filter_resolucion_numero',
                    'N° Resolución:',
                    'label-input-y-100',
                    old('filter_resolucion_numero', $filters->filter_resolucion_numero ?? null),
                    ['' => 'Cualquiera'] + $carreraM->numerosResolucion(),
                ),
                $form->select(
                    'filter_resolucion_anio',
                    'Año Resolución:',
                    'label-input-y-100',
                    old('filter_resolucion_anio', $filters->filter_resolucion_anio ?? null),
                    ['' => 'Cualquiera'] + $carreraM->aniosResolucion(),
                ),
                $form->select(
                    'filter_nombre',
                    'Nombre de carrera:',
                    'label-input-y-100',
                    old('filter_nombre', $filters->filter_nombre ?? null),
                    ['' => 'Cualquiera'] + $carreraM->listadoNombres(),
                ),
            ],
            'fields' => [
                'nombre' => 'Nombre',
                'resolucion_numero' => 'N° de Resolución',
                'resolucion_anio' => 'Año de la Resolución',
            ],

        ]) ?>
    </div>

    <table class="table__body">
        <thead>
            <tr>
                <th>Carrera</th>
                <th class="center">Resolución</th>
                <th class="center">Apertura</th>
                <th class="center">Cierre</th>
                <th class="center">Estado</th>
                <th class="center">Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($carreras as $carrera)
            <tr>
                <td class="bold">{{ $carrera->nombre }}</td>
                <td class="center">{{ $carrera->resolucion }}</td>
                <td class="center">{{ $carrera->anio_apertura }}</td>
                <td class="center">{{ $carrera->anio_fin ?? '-' }}</td>
                <td class="center">{{ $carrera->vigente == 1 ? 'Vigente' : 'No vigente' }}</td>
                <td class="flex just-center">
                    <div>
                        <a href="{{ route('admin.carreras.edit', ['carrera' => $carrera]) }}">
                            <button class="btn_blue">
                                <i class="ti ti-file-info"
                                    style="font-size: 1.3em; margin-right: 8px;"></i>Modificar
                            </button>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="w-1/2 mx-auto p-5 pagination">
    {{ $carreras->appends(request()->query())->links('Componentes.pagination') }}
</div>
@endsection