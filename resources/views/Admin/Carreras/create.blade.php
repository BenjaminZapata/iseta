@extends('Admin.template')

@section('content')
<div>
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVA CARRERA'])
        <div class="perfil__info">

         <?= $form->generate(
    route('admin.carreras.store'),
    'post',
    [
        'Información' => [
            $form->text('nombre', 'Nombre:*', 'label-input-y-75', null, [
                'placeholder' => 'Ej: Ingeniería en Sistemas'
            ]),
            $form->text('resolucion', 'Resolución:*', 'label-input-y-75', null, [
                'placeholder' => 'Ej: Res. 123/2020'
            ]),
            $form->text('anio_apertura', 'Año de apertura:*', 'label-input-y-75', null, [
                'placeholder' => 'Ej: 2024',
                'inputmode'   => 'numeric'
            ]),
            $form->text('anio_fin', 'Año de cierre:', 'label-input-y-75', null, [
                'placeholder' => 'Ej: 2028 (si aplica)',
                'inputmode'   => 'numeric'
            ]),
            $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', null, [
                'placeholder' => 'Notas adicionales sobre la carrera'
            ]),
            $form->file('resolucion_archivo', 'Archivo de la resolución (PDF):', 'label-input-y-75', null, [
                'accept' => '.pdf'
            ])
        ]
    ]
) ?>

        </div>
    </div>
</div>
@endsection