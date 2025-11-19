@extends('Admin.template')
@php use Illuminate\Support\HtmlString; @endphp

<link rel="stylesheet" href="{{ asset('css/Admin/Examenes/edit-examen.css') }}">

@section('content')
<div class="edit-form-container">
    <div class="perfil_one br">

        @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR FICHA DE EXAMEN'])

        {{-- ===============================
             DATOS DE MESA
        ================================ --}}
        <fieldset class="p-2" style="margin:10px;">
            <legend class="font-600 font-7">Mesa de Examen</legend>
            <div class="gap-2 p-0">
                <div class="perfil_dataname">
                    <label>Carrera:</label>
                    <span class="campo_info2">
                        {{ $examen->asignatura->carrera->first()->nombre ?? 'Sin carrera asociada' }}
                    </span>
                </div>

                <div class="perfil_dataname">
                    <label>Materia:</label>
                    <span class="campo_info2">
                        {{ $examen->asignatura->nombre }}
                    </span>
                </div>

                <div class="perfil_dataname border-none">
                    <label>Fecha de mesa:</label>
                    <span class="campo_info2">
                        {{ $examen->mesa->fecha ? $formatoFecha->dmahm($examen->mesa->fecha) : 'No hay datos sobre la fecha' }}
                    </span>
                </div>
            </div>
        </fieldset>

        {{-- ===============================
             DATOS DEL ALUMNO
        ================================ --}}
        <fieldset class="p-2" style="margin:10px;">
            <legend class="font-600 font-7">Alumno</legend>
            <div class="perfil_dataname">
                <label>Nombre:</label>
                <span class="campo_info2">
                    {{ $examen->alumno->apellidoNombre() }}
                </span>
            </div>

            <div class="perfil_dataname border-none">
                <label>DNI:</label>
                <span class="campo_info2">{{ $examen->alumno->dniPuntos() }}</span>
            </div>
        </fieldset>

        {{-- ===============================
             FORMULARIO DE EDICIÓN
        ================================ --}}
        <div class="perfil__info" style="margin:10px;">
           <form method="POST" action="{{ route('admin.examenes.update', $examen->id) }}">
    @csrf
    @method('PUT')

    {!! $form->generate($examen, 'put', [

        'Datos del examen' => [

           $form->select(
    'asistencia',
    'Asistencia:',
    'label-input-y-75',
    old('asistencia') ?? $examen,
    [
        '' => 'Seleccionar asistencia',
        '1' => 'Presente',
        '0' => 'Ausente'
    ],
),

            $form->text(
                'nota',
                'Nota:',
                'label-input-y-75',
                old('nota') ?? $examen,
                ['type' => 'number', 'step' => '1', 'min' => '1', 'max' => '10']
            ),

            $form->select(
                'tipo_final',
                'Tipo de final:',
                'label-input-y-75',
                old('tipo_final') ?? $examen,
                [
                    null => 'Seleccionar tipo de final',
                    1 => 'Escrito',
                    2 => 'Oral',
                    3 => 'Promocionado',
                    4 => 'Equivalencia'
                ]
            ),

            $form->text(
                'libro',
                'Libro:',
                'label-input-y-75',
                old('libro') ?? $examen,
                ['type' => 'number', 'maxlength' => 4]
            ),

            $form->text(
                'acta',
                'Acta:',
                'label-input-y-75',
                old('acta') ?? $examen,
                ['type' => 'number', 'maxlength' => 4]
            ),

        ],

    ]) !!}
</form>
        </div>

        {{-- ===============================
             ELIMINAR EXAMEN
        ================================ --}}
        <div class="boton-eliminar">

            @if (!$config['modo_seguro'])
            <div>
                <form method="POST" id="form-eliminar-{{ $examen->id }}"
                      action="{{ route('admin.examenes.destroy', ['examen' => $examen->id]) }}">
                    @csrf
                    @method('delete')

                    <button type="button" onclick="openGeneralModal(
                        'form-eliminar-{{ $examen->id }}',
                        '¿Estás seguro de que querés eliminar este examen?\n\n' +
                        'Alumno: {{ $examen->alumno?->apellidoNombre() ?? "No asignado" }}\n' +
                        'Carrera: {{ $examen->asignatura->carrera->first()->nombre ?? "No asignada" }}\n' +
                        'Asignatura: {{ $examen->asignatura?->nombre ?? "No asignada" }}\n' +
                        'Fecha de Mesa: {{ $examen->mesa?->fecha ? \Carbon\Carbon::parse($examen->mesa->fecha)->format("d/m/Y") : "No definida" }}\n' +
                        'Nota: {{ $examen->nota ?? "Sin nota" }}\n' +
                        'Asistencia: {{ $examen->asistenciaTexto() ?? "Sin datos" }}\n\n' +
                        'ESTA ACCIÓN NO SE PUEDE DESHACER.'
                    )"
                        class="btn_red_outline">
                        <i class="ti ti-trash" style="font-size: 1.3em;"></i>
                        <span>Eliminar ficha de examen</span>
                    </button>
                </form>
            </div>
            @endif

        </div>
    </div>

</div>

<script src="{{ asset('js/confirmacion.js') }}"></script>
@endsection
