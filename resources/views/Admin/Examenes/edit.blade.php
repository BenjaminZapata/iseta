@extends('Admin.template')
@php use Illuminate\Support\HtmlString; @endphp

@section('content')
<div class="edit-form-container">
    <div class="perfil_one br">

        @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR FICHA DE EXAMEN'])

        <div class="perfil__info">
            <form method="post" action="{{ route('admin.examenes.update', ['examen' => $examen->id]) }}">
                @csrf
                @method('put')

               <fieldset class="p-2" style="margin:10px;">
                    <legend class="font-600 font-7">Mesa de Examen</legend>
                    <div class="gap-2 p-0">
                        <div class="perfil_dataname">
                            <label>Carrera:</label>
                            <span class="campo_info2">
                                <a class="flex items-center">
                                    {{ $examen->asignatura->carrera->first()->nombre }}
                                </a>
                            </span>
                        </div>

                        <div class="perfil_dataname">
                            <label>Materia:</label>
                            <span class="campo_info2">
                                <a class="capitalize flex items-center">
                                    {{ $examen->asignatura->nombre }}
                                </a>
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

                <fieldset class="p-2" style="margin:10px;">
                    <legend class="font-600 font-7">Alumno</legend>
                    <div class="perfil_dataname">
                        <label>Nombre:</label>
                        <span class="campo_info2">
                            <a class="capitalize flex items-center">
                                {{ $examen->alumno->apellidoNombre() }}
                            </a>
                        </span>
                    </div>

                    <div class="perfil_dataname border-none">
                        <label>DNI:</label>
                        <span class="campo_info2">{{ $examen->alumno->dniPuntos() }}</span>
                    </div>
</fieldset>
            </form>
        </div>

        <div class="perfil__info">
            <form method="POST" action="{{ route('admin.examenes.update', $examen->id) }}">
                @csrf
                @method('PUT')

                {!! $form->generate($examen, 'put', [
                    'Datos del examen' => [
                        // Nota
                        $form->text(
                            'nota',
                            'Nota:',
                            'label-input-y-75',
                            old('nota', $examen->nota),
                            ['type' => 'number', 'step' => '0.01', 'min' => '0', 'max' => '10']
                        ),

                        // Tipo de final
                        $form->select(
                            'tipo_final',
                            'Tipo de final:',
                            'label-input-y-75',
                            old('tipo_final', $examen->tipo_final),
                            [
                                1 => 'Escrito',
                                2 => 'Oral',
                                3 => 'Promocionado',
                                4 => 'Equivalencia'
                            ]
                        ),

                        // Libro
                        $form->text(
                            'libro',
                            'Libro:',
                            'label-input-y-75',
                            old('libro', $examen->libro),
                            ['type' => 'text', 'maxlength' => 20]
                        ),

                        // Acta
                        $form->text(
                            'acta',
                            'Acta:',
                            'label-input-y-75',
                            old('acta', $examen->acta),
                            ['type' => 'text', 'maxlength' => 20]
                        ),

                        $form->select()
                    ],
                ]) !!}
            </form>
        </div>

        <div class="boton-eliminar">
            @if (!$config['modo_seguro'])
                <div>
                    <form method="POST" class="form-eliminar"
                        action="{{ route('admin.examenes.destroy', ['examen' => $examen->id]) }}">
                        @csrf
                        @method('delete')

                        <button class="btn_red_outline"
                            onclick="openGeneralModal(
                                'form-eliminar-{{ $examen->id }}',
                                '¿Estás seguro de que querés eliminar la ficha de examen de: {{ strtoupper($examen->alumno->apellido) }} {{ strtoupper($examen->alumno->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.'
                            )"
                            class="btn_icon-danger" style="margin-left: 10px;">
                            <i class="ti ti-trash" style="font-size: 1.3em;"></i>
                            Eliminar ficha de examen
                        </button>
                    </form>
                </div>
            @endif
        </div>

    </div>
</div>

<script src="{{ asset('js/confirmacion.js') }}"></script>
@endsection
