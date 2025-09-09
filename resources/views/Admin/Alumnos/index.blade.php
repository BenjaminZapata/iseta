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



{{-- CONTENT --}}

<div class="table" data-name="tablaAlumnos">


    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE ALUMNOS'])

    {{-- BOTON CREAR --}}

    <div class="perfil__header-alt" style="display: flex; align-items: center; gap: 1rem;">
    <a href="{{ route('admin.alumnos.create') }}">
        <button class="btn_blue">
            <i class="ti ti-circle-plus"></i>Agregar alumno
        </button>
    </a>

    {{-- FILTROS --}}
    <?= $filtergen->generate('admin.alumnos.index', $filters, [
        'dropdowns' => [
            $carreraM->dropdown(
                'filter_carrera_id',
                'Carrera:',
                'label-input-y-100',
                old('filter_carrera_id', $filters->filter_carrera_id ?? null),
                ['first_items' => ['Todas']]
            ),

            $form->select(
                'filter_ciudad',
                'Ciudad:',
                'label-input-y-100',
                old('filter_ciudad', $filters->filter_ciudad ?? null),
                ['' => 'Cualquiera'] + $alumnoM->ciudades()
            ),

            $form->select(
                'filter_titulo',
                'Estado del título:',
                'label-input-y-100',
                old('filter_titulo', $filters->filter_titulo ?? null),
                [
                    null => 'Todos',
                    0 => 'No entregado',
                    1 => 'Certificado de constancia de título en trámite',
                    2 => 'Constancia de alumno del último año del nivel secundario',
                    3 => 'Fotocopia del título original secundario',
                ]
            ),

            $form->checkbox(
                'filter_vencido',
                'Solo títulos vencidos',
                'label-input-y-100',
                old('filter_vencido', $filters->filter_vencido ?? false)
            ),
        ],
        'fields' => [
            'alumno' => 'Alumno',
            'dni' => 'Dni',
            'telefono1' => 'Telefono',
            'titulo_secundario' => 'Titulo'
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


        {{-- TBODY --}}
        <tbody>
            @foreach ($alumnos as $alumno)
            <tr>
                <td class="capitalize">
                    <p class="bold" style="text-transform: uppercase;">{{ $alumno->apellidoNombre() }}</p>
                    <p>dni: {{ $alumno->dniPuntos() }}</p>
                </td>

                <td>
                    <p style="text-transform: none;">{{ $alumno->email ? $alumno->email : 'Sin mail registrado' }}
                    </p>
                    @if ($alumno->telefono1)
                    <p>tel: {{ $alumno->telefono1 }}</p>
                    @elseif ($alumno->telefono2)
                    <p>tel: {{ $alumno->telefono2 }}</p>
                    @elseif ($alumno->telefono3)
                    <p>tel: {{ $alumno->telefono3 }}</p>
                    @else
                    <p>tel: Sin telefono</p>
                    @endif
                </td>
                <td>
                    <p>{{ $alumno->ciudad }}</p>
                    <p>{{ $alumno->calle }} {{ $alumno->casa_numero ? $alumno->casa_numero : '' }}</p>
                </td>
                <td class="flex just-center">
                    <div style="display: flex; justify-content: center;">
                        <a href="{{ route('admin.alumnos.edit', ['alumno' => $alumno->id]) }}">
                            <button class="btn_blue"><i class="ti ti-file-info"
                                    style="font-size: 1.3em; margin-right: 8px;"></i>Modificar</button>
                        </a>
                        @if (!$config['modo_seguro'])
                        <div>
                            <form method="POST" class="form-eliminar"
                                action="{{ route('admin.alumnos.destroy', ['alumno' => $alumno->id]) }}"
                                style="margin-left: 10px;">
                                @csrf
                                @method('delete')
                                <button class="btn_icon-danger" style="background-color: red"
                                    onclick="openGeneralModal('form-eliminar-{{ $alumno->id }}', '¿Estás seguro de que querés eliminar al alumno: {{ strtoupper($alumno->apellido)}} {{ strtoupper($alumno->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                    class="btn_icon-danger" style="background-color: red; margin-left: 10px;">
                                    <i class="ti ti-trash" style="font-size: 1.3em;"></i>
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
    {{ $alumnos->appends(request()->query())->links('Componentes.pagination') }}
</div>

@endsection
