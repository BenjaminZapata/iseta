@extends('secretario.template')

@section('content')
@php
    // Definimos los fieldsets directamente en la vista
    $fieldsets = [
        'Información personal' => [
            $form->text('dni', 'DNI:', 'label-input-y-75', $alumno),
            $form->text('nombre', 'Nombre:', 'label-input-y-75', $alumno),
            $form->text('apellido', 'Apellido:', 'label-input-y-75', $alumno),
            $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', $alumno, [
                'default' => $alumno->fecha_nacimiento->format('Y-m-d')
            ]),
            $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', $alumno, [
                'Soltero', 'Casado', 'Divorciado', 'Viudo', 'Conyuge', 'Otro'
            ]),
            $form->text('genero', 'Género:', 'label-input-y-75', $alumno)
        ],
        'Dirección' => [
            $form->text('ciudad', 'Ciudad:', 'label-input-y-75', $alumno),
            $form->text('codigo_postal', 'Código postal:', 'label-input-y-75', $alumno),
            $form->text('calle', 'Calle:', 'label-input-y-75', $alumno),
            $form->text('casa_numero', 'Altura:', 'label-input-y-75', $alumno),
            $form->text('dpto', 'Dpto:', 'label-input-y-75', $alumno),
            $form->text('piso', 'Piso:', 'label-input-y-75', $alumno)
        ],
        'Académico' => [
            $form->text('titulo_anterior', 'Título anterior:', 'label-input-y-75', $alumno),
            $form->text('becas', 'Becas:', 'label-input-y-75', $alumno),
            $form->text('nombre_institucion_secundario', 'Institución secundaria:', 'label-input-y-75', $alumno),
            $form->text('titulo_secundario', 'Título secundario:', 'label-input-y-75', $alumno)
        ],
        'Contacto' => [
            $form->text('email', 'Email:', 'label-input-y-75', $alumno),
            $form->text('telefono1', 'Teléfono 1:', 'label-input-y-75', $alumno),
            $form->text('telefono2', 'Teléfono 2:', 'label-input-y-75', $alumno),
            $form->text('telefono3', 'Teléfono 3:', 'label-input-y-75', $alumno)
        ],
        'Otros' => [
            $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', $alumno)
        ]
    ];
@endphp

<div class="edit-form-container">
    <div class="perfil_one br">

        @include('components.header-avatar', ['tituloSeccion' => 'DETALLE DEL ALUMNO/A'])

        {{-- Formulario en modo solo lectura --}}
        <form class="perfil__info form-readonly" method="get" action="#" onsubmit="return false;">
            @foreach ($fieldsets as $legend => $inputs)
                <fieldset class="p-2" style="margin: 10px;">
                    <legend class="font-600 font-7">{{ $legend }}</legend>
                    <div class="grid-2 gap-2 p-0">
                        @foreach ($inputs as $input)
                            {{-- Forzamos que todos los campos estén deshabilitados --}}
                            {!! preg_replace('/(<(input|select|textarea)\b)/i', '$1 disabled', $input) !!}
                        @endforeach
                    </div>
                </fieldset>
            @endforeach

            {{-- Bloque de botones agregado --}}
            <div class="botones-derecha">
                <x-botones-alumno />
                <x-btn-cancelar />
            </div>
        </form>

        <style>
            /* Estilo para campos deshabilitados en formularios de solo lectura */
            .form-readonly input[disabled],
            .form-readonly select[disabled],
            .form-readonly textarea[disabled] {
                background-color: transparent !important;
                border: none !important;
                color: #333 !important;
                padding-left: 0;
                box-shadow: none;
                cursor: default;
                font-weight: 500;
            }

            /* Evita que el texto se vea grisáceo */
            .form-readonly input[disabled]::placeholder,
            .form-readonly select[disabled],
            .form-readonly textarea[disabled] {
                color: #333 !important;
            }
        </style>

    </div>
</div>
@endsection
