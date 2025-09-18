@extends('secretario.template')

@section('content')
<style>
    #filters .label-input-y-100 label {
        text-align: left !important;
        display: block !important;
        width: 100%;
        padding-top: 15px;
    }
</style>

{{-- CONTENT --}}
<div class="table" data-name="tablaAlumnos">

    {{-- HEADER --}}
    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE ALUMNOS'])

    {{-- FILTROS --}}
    <?= $filtergen->generate('secretario.alumnos.index', $filters, [
        'dropdowns' => [
            $carreraM->dropdown(
                'filter_carrera_id',
                'Carrera:',
                'label-input-y-100',
                $filters,
                ['first_items' => ['Todas']]
            ),
            $form->select(
                'filter_ciudad',
                'Ciudad:',
                'label-input-y-100',
                $filters->filter_ciudad ?? null,
                $alumnoM->ciudades()
            ),
            $form->select(
                'filter_titulo',
                'Estado del título:',
                'label-input-y-100',
                $filters->filter_titulo ?? 0,
                [
                    0 => 'Todos',
                    1 => 'Fotocopia del título original secundario',
                    2 => 'Certificado de constancia de título en trámite',
                    3 => 'Constancia de alumno del último año del nivel secundario',
                    4 => 'No entregado',
                ]
            ),
        ],
        'fields' => [
            'alumno' => 'Alumno',
            'dni' => 'Dni',
            'telefono1' => 'Teléfono',
            'titulo_secundario' => 'Título'
        ],
    ]) ?>

</div>

{{-- TABLA --}}
<table class="table__body">
    {{-- HEADER --}}
    <thead>
        <tr>
            <th>Alumno</th>
            <th>Contacto</th>
            <th>Dirección</th>
            <th class="center">Acción</th>
        </tr>
    </thead>

    {{-- BODY --}}
    <tbody>
        @foreach ($alumnos as $alumno)
            <tr>
                {{-- Alumno --}}
                <td class="capitalize">
                    <p class="bold" style="text-transform: uppercase;">
                        {{ $alumno->apellidoNombre() }}
                    </p>
                    <p>dni: {{ $alumno->dniPuntos() }}</p>
                </td>

                {{-- Contacto --}}
                <td>
                    <p style="text-transform: none;">
                        {{ $alumno->email ? $alumno->email : 'Sin mail registrado' }}
                    </p>
                    @if ($alumno->telefono1)
                        <p>tel: {{ $alumno->telefono1 }}</p>
                    @elseif ($alumno->telefono2)
                        <p>tel: {{ $alumno->telefono2 }}</p>
                    @elseif ($alumno->telefono3)
                        <p>tel: {{ $alumno->telefono3 }}</p>
                    @else
                        <p>tel: Sin teléfono</p>
                    @endif
                </td>

                {{-- Dirección --}}
                <td>
                    <p>{{ $alumno->ciudad }}</p>
                    <p>{{ $alumno->calle }} {{ $alumno->casa_numero ? $alumno->casa_numero : '' }}</p>
                </td>

                {{-- Acción --}}
                <td class="flex just-center">
                    <div style="display: flex; justify-content: center;">
                        <a href="{{ route('secretario.alumnos.show', ['alumno' => $alumno->id]) }}">
                            <button class="btn_blue">
                                <i class="ti ti-file-info" style="font-size: 1.3em; margin-right: 8px;"></i>
                                Modificar
                            </button>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- PAGINACIÓN --}}
<div class="w-full flex justify-center p-5 pagination">
    {{ $alumnos->appends(request()->query())->links('Componentes.pagination') }}
</div>
@endsection
