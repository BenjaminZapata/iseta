@extends('Admin.template')

@section('content')
<div>
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVA CARRERA',
        'breadcrumbs' => [
        ['label' => 'Carreras', 'url' => route('admin.carreras.index')],
        ['label' => 'Crear Carrera', 'url' => route('admin.carreras.create')],
        ]
        ])
        <div class="perfil__info">

            <?= $form->generate(route('admin.carreras.store'), 'post', [
                'Información' => [
                    $form->text('nombre', 'Nombre:', 'label-input-y-75'),
                    $form->text('resolucion', 'Resolucion:', 'label-input-y-75'),
                    $form->text('anio_apertura', 'Año de apertura:', 'label-input-y-75'),
                    $form->text('anio_fin', 'Año de cierre:', 'label-input-y-75'),
                    $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75')
                ]
            ]) ?>
        </div>
    </div>
</div>
@endsection