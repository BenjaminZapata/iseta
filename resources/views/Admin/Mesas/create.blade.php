@extends('Admin.template')
@php use Illuminate\Support\HtmlString; @endphp

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR MESA DE EXAMEN'])

            <div class="perfil__info">

                <form method="post" action="{{ route('admin.mesas.store') }}">
                    @csrf

                    {!! $form->generate(null, 'post', [

                      'Carrera y Asignatura' => [
    // Select de carrera con validación
    $form->select(
        'carrera',
        'Seleccionar carrera:',
        'label-input-y-75',
        $oldCarrera,
        $opcionesCarreras,
        ['id' => 'carrera_select', 'required' => true]
    ) .

    // Select de asignatura con validación
    $form->select(
        'id_asignatura',
        'Seleccionar la asignatura:',
        'label-input-y-75',
        $oldAsignatura,
        $opcionesAsignaturas,
        ['id' => 'asignatura_select']
    ) 
],


                        'Profesores' => [
                            $form->select('prof_presidente', 'Presidente de mesa:', 'label-input-y-75', $oldPresidente, $opcionesProfesores),
                            $form->select('prof_vocal_1', 'Profesor vocal 1:', 'label-input-y-75', $oldVocal1, $opcionesProfesores),
                            $form->select('prof_vocal_2', 'Profesor vocal 2:', 'label-input-y-75', $oldVocal2, $opcionesProfesores),
                        ],

                        'Llamados y fecha' => [
                            $form->select('cantidad_llamados', 'Cantidad de llamados:', 'label-input-y-75', $oldCantidadLlamados, [
                                '1' => '1 llamado',
                                '2' => '2 llamados',
                            ], ['id' => 'cantidad_llamados']),
                            new HtmlString('
                                <div class="label-input-y-75" id="fecha_llamado_1">
                                    <label for="fecha_1">Fecha:</label>
                                    <input class="campo_info rounded" type="datetime-local" name="fecha_1" value="' . e($oldFecha1) . '">
                                </div>'),
                            new HtmlString('
                                <div class="label-input-y-75" id="fecha_llamado_2">
                                    <label for="fecha_2">Fecha llamado 2:</label>
                                    <input class="campo_info rounded" type="datetime-local" name="fecha_2" value="' . e($oldFecha2) . '">
                                </div>'),
                        ],
                        'Otros' => [
                            $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', old('observaciones'), [
                                'placeholder' => 'Notas adicionales sobre la mesa de examen',
                                'maxlength' => 150,
                            ]),
                        ]
                    ]) !!}
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/obtener-materias.js') }}"></script>
    <script src="{{ asset('js/llamados.js') }}"></script>
@endsection
