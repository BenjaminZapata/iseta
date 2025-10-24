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
                <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>Agregar alumno
            </button>
        </a>

        {{-- FILTROS --}}
        <?= $filtergen->generate('admin.alumnos.index', $filters, [
            'dropdowns' => [
                $form->select('filter_titulo', 'Estado del título:', 'label-input-y-100', old('filter_titulo', $filters->filter_titulo ?? null), [
                    null => 'Todos',
                    0 => 'No entregado',
                    1 => 'Certificado de constancia de título en trámite',
                    2 => 'Constancia de alumno del último año del nivel secundario',
                    3 => 'Fotocopia del título original secundario',
                ]),

                $form->select('filter_vencido', 'Plazo de entrega del título:', 'label-input-y-100', old('filter_vencido', $filters->filter_vencido ?? 'null'), [
                    null => 'Todos',
                    1 => 'Vencido',
                ]),
            ],
            'fields' => [
                'nombre' => 'Nombre',
                'apellido' => 'Apellido',
                'dni' => 'Dni',
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
                <th>Academico</th>
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
                <td>
                    @php
                    $titulo = [
                    'No entregado',
                    'Fotocopia del título original secundario',
                    'Certificado de constancia de título en trámite',
                    'Constancia de alumno del último año del nivel secundario',
                    ];
                    @endphp
                    <p class="bold" style="text-transform: uppercase;">titulo:
                        {{ $titulo[$alumno->titulo_secundario] }}
                    </p>
                    <p>estado: {{ $alumno->egresado?->estado_texto ?? 'Sin inscripción' }}</p>



                </td>
                <td class="flex just-center" style="min-width: 170px;">
                    <div style="display: flex; justify-content: center; gap: 10px;">
                        <a href="{{ route('admin.alumnos.edit', ['alumno' => $alumno->id]) }}">
                            <button class="btn_blue btn_contraible">
                                <i class="ti ti-pencil" style="font-size: 1.3em;"></i>
                                <span class="btn-text">Editar</span>
                            </button>
                        </a>
                        @if (!$config['modo_seguro'])
                        <form id="form-eliminar-{{ $alumno->id }}"
                            action="{{ route('admin.alumnos.destroy', $alumno->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                onclick="openGeneralModal('form-eliminar-{{ $alumno->id }}',
                                    '¿Estás seguro de que querés eliminar al alumno: {{ strtoupper($alumno->apellido) }} {{ strtoupper($alumno->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                class="btn_icon-danger btn_contraible" style="background-color: red;">
                                <i class="ti ti-trash"></i>
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
</div>

{{-- PAGINACIÓN --}}
<div class="w-full flex justify-center p-5 pagination">
    {{ $alumnos->appends(request()->query())->links('Componentes.pagination') }}
</div>
@endsection