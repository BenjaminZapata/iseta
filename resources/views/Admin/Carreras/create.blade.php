@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Crear nueva carrera</h2>
            </div>
            <div class="perfil__info">

                <?= $form->generate(route('admin.carreras.store'),'post',[
                    'Información' => [
                        $form->text('nombre', 'Nombre:','label-input-y-75'),
                        $form->text('resolucion', 'Resolucion:','label-input-y-75'),
                        $form->text('anio_apertura', 'Año de apertura:','label-input-y-75'),
                        $form->text('anio_fin', 'Año de cierre:','label-input-y-75'),
                        $form->textarea('observaciones', 'Observaciones:','label-input-y-75')
                    ]
                ]) ?>
            </div>
        </div>
    </div>
@endsection
