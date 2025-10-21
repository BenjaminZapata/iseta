@extends('secretario.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVO PROFESOR/A'])
            <div class="perfil__info">
                <?= $form->generate(route('secretario.profesores.store'), 'post', [
                        'Profesor' => [$form->text('nombre', 'Nombre:', 'label-input-y-75', null), $form->text('apellido', 'Apellido:', 'label-input-y-75', null), $form->text('dni', 'DNI:', 'label-input-y-75', null), $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', null), $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', null, ['vacio', 'Soltero', 'Casado', 'Divorciado', 'Viudo', 'Conyuge', 'Otro']), $form->text('lugar_nacimiento', 'Lugar de nacimiento:', 'label-input-y-75', null)],
                        'Dirección' => [$form->text('ciudad', 'Ciudad:', 'label-input-y-75', null), $form->text('codigo_postal', 'Codigo postal:', 'label-input-y-75', null), $form->text('calle', 'Calle:', 'label-input-y-75', null), $form->text('casa_numero', 'Numero de casa:', 'label-input-y-75', null), $form->text('dpto', 'Departamento:', 'label-input-y-75', null), $form->text('piso', 'Piso:', 'label-input-y-75', null)],
                        'Academico' => [$form->text('formacion_academica', 'Formacion academica:', 'label-input-y-75', null), $form->text('anio_ingreso', 'Año de ingreso:', 'label-input-y-75', null)],
                        'Contacto' => [$form->text('email', 'Email:', 'label-input-y-75', null), $form->text('telefono1', 'Telefono 1:', 'label-input-y-75', null), $form->text('telefono2', 'Telefono 2:', 'label-input-y-75', null), $form->text('telefono3', 'Telefono 3:', 'label-input-y-75', null)],
                        'Otros' => [$form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', null)],
                    ]) ?>
            </div>
        </div>
    </div>
@endsection
