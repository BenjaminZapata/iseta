@extends('Admin.template')

@section('content')
<div>
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'CREAR ASIGNATURA'])

        <div class="perfil__info">

            <form action="{{ route('admin.asignaturas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <p class="info-obligatorios">
                    Los campos marcados con <span style="color:red">*</span> son obligatorios.
                </p>

                <?= $form->generate(null, 'post', [
                    'Asignatura' => [
                        $form->text('nombre', 'Nombre: *', 'label-input-y-75', old('nombre'), [
                            'placeholder' => 'Ej: Biología I',
                            'maxlength' => 50,
                        ]),
                        $form->text('carga_horaria', 'Cantidad de modulos: *', 'label-input-y-75', old('carga_horaria'), [
                            'placeholder' => 'Ej: 1',
                        ]),
                    ],
                    'Otros' => [
                        $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', old('observaciones'), [
                            'placeholder' => 'Notas adicionales sobre el profesor/a',
                            'maxlength' => 150,
                        ]),
                    ],
                ]) ?>

            </form>
        </div>
    </div>
</div>
@endsection