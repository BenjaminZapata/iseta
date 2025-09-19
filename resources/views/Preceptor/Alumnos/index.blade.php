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

<div class="table" data-name="tablaAlumnos">

    {{-- Header dinámico con avatar --}}
   @include('preceptor.header-avatar', ['tituloSeccion' => 'GESTIÓN DE ALUMNOS'])


    {{-- Acciones y filtros --}}
    <div class="perfil__header-alt d-flex align-items-center gap-1rem">
        <a href="{{ route('preceptor.alumnos.create') }}">
            <button class="btn_blue">
                <i class="ti ti-circle-plus"></i>Agregar alumno
            </button>
        </a>

        {{-- Filtros --}}
        {!! $filtergen->generate('preceptor.alumnos.index', $filters, [
            'dropdowns' => [
                $carreraM->dropdown('filter_carrera_id', 'Carrera:', 'label-input-y-100', old('filter_carrera_id', $filters->filter_carrera_id ?? null), ['first_items' => ['Todas']]),
                $form->select('filter_ciudad', 'Ciudad:', 'label-input-y-100', old('filter_ciudad', $filters->filter_ciudad ?? null), ['' => 'Cualquiera'] + $alumnoM->ciudades()),
                $form->select('filter_titulo', 'Estado del título:', 'label-input-y-100', old('filter_titulo', $filters->filter_titulo ?? null), [
                    null => 'Todos',
                    0 => 'No entregado',
                    1 => 'Certificado de constancia de título en trámite',
                    2 => 'Constancia de alumno del último año del nivel secundario',
                    3 => 'Fotocopia del título original secundario',
                ]),
                $form->checkbox('filter_vencido', 'Solo títulos vencidos', 'label-input-y-100', old('filter_vencido', $filters->filter_vencido ?? false)),
            ],
            'fields' => [
                'alumno' => 'Alumno',
                'dni' => 'Dni',
                'telefono1' => 'Telefono',
                'titulo_secundario' => 'Titulo'
            ],
        ]) !!}
    </div>

    {{-- Tabla de alumnos --}}
    <table class="table__body">
        <thead>
            <tr>
                <th>Alumno</th>
                <th>Contacto</th>
                <th>Dirección</th>
                <th class="center">Lugar de nacimiento</th>
                <th class="center">Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alumnos as $alumno)
            <tr>
                <td class="capitalize">
                    <p class="bold text-uppercase">{{ $alumno->apellidoNombre() }}</p>
                    <p>DNI: {{ $alumno->dniPuntos() }}</p>
                </td>
                <td>
                    <p>{{ $alumno->email ?? 'Sin mail registrado' }}</p>
                    <p>
                        Tel: 
                        {{ $alumno->telefono1 ?? $alumno->telefono2 ?? $alumno->telefono3 ?? 'Sin teléfono' }}
                    </p>
                </td>
                <td>
                    <p>{{ $alumno->ciudad }}</p>
                    <p>{{ $alumno->calle }} {{ $alumno->casa_numero ?? '' }}</p>
                </td>
                <td class="center">
                    <p>{{ $alumno->lugar_nacimiento }}</p>
                </td>
                <td class="flex just-center">
                    <a href="{{ route('preceptor.alumnos.edit', ['alumno' => $alumno->id]) }}">
                        <button class="btn_blue">
                            <i class="ti ti-file-info me-2"></i>Modificar
                        </button>
                    </a>

                    @if (!$config['modo_seguro'])
                    <form method="POST" class="form-eliminar ms-2"
                          action="{{ route('preceptor.alumnos.destroy', ['alumno' => $alumno->id]) }}">
                        @csrf
                        @method('delete')
                        <button class="btn_icon-danger"
                                onclick="openGeneralModal('form-eliminar-{{ $alumno->id }}', '¿Estás seguro de que querés eliminar al alumno: {{ strtoupper($alumno->apellido) }} {{ strtoupper($alumno->nombre) }}? \n\nESTA ACCIÓN NO SE PUEDE DESHACER.')">
                            <i class="ti ti-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Paginación --}}
<div class="w-full flex justify-center p-5 pagination">
    {{ $alumnos->appends(request()->query())->links('Componentes.pagination') }}
</div>
@endsection
