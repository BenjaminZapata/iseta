@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVA CARRERA'])
            <nav aria-label="breadcrumb" class="mb-4">
                <ul class="breadcrumb flex items-center gap-2 text-sm text-gray-700">
                    <li class="flex items-center">
                        <a href="/admin/carreras">Carreras</a>
                    </li>
                </ul>
            </nav>
            <div class="perfil__info">

                <?= $form->generate(route('admin.carreras.store'), 'post', [
                        'Información' => [$form->text('nombre', 'Nombre:', 'label-input-y-75'), $form->text('resolucion', 'Resolucion:', 'label-input-y-75'), $form->text('anio_apertura', 'Año de apertura:', 'label-input-y-75'), $form->text('anio_fin', 'Año de cierre:', 'label-input-y-75'), $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75')],
                    ]) ?>
            </div>
        </div>
    </div>
@endsection
