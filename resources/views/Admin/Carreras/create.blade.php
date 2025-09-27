@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVA CARRERA'])
            <div class="perfil__info">

                <form action="{{ route('admin.carreras.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <?= $form->generate(null, 'post', [
                        'Información' => [
                            $form->text('nombre', 'Nombre:', 'label-input-y-75'),
                            $form->text('resolucion', 'Resolución:', 'label-input-y-75'),
                            $form->text('anio_apertura', 'Año de apertura:', 'label-input-y-75'),
                            $form->text('anio_fin', 'Año de cierre:', 'label-input-y-75'),
                            $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75'),
                        ]
                    ]) ?>

                    <div class="label-input-y-75 mt-3">
                        <label for="resolucion_archivo" class="form-label">Archivo de la resolución (PDF):</label>
                        <input type="file" name="resolucion_archivo" accept="application/pdf" class="form-control">
                    </div>
                    <div class="mt-4">
    <button type="submit" class="btn btn-primary">Guardar carrera</button>
</div>

                </form>
            </div>
        </div>
    </div>
@endsection
