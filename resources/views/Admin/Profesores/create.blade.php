@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVO PROFESOR/A'])
            <nav aria-label="breadcrumb" class="mb-4">
                <ul class="breadcrumb flex items-center gap-2 text-sm text-gray-700">
                    <li class="flex items-center">
                        <a href="/admin/profesores">Profesores</a>
                    </li>
                    <li>
                        <span class="text-gray-500" style="color: black;"> Crear profesor/a</span>
                    </li>
                </ul>
            </nav>
            <div class="perfil__info">
                <?= $form->generate(route('admin.profesores.store'), 'post', [
                        'Profesor' => [$form->text('dni', 'DNI:', 'label-input-y-75', null), $form->text('nombre', 'Nombre:', 'label-input-y-75', null), $form->text('apellido', 'Apellido:', 'label-input-y-75', null), $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', null), $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', null, ['vacio', 'Soltero', 'Casado', 'Divorciado', 'Viudo', 'Conyuge', 'Otro'])],
                        'Dirección' => [$form->text('ciudad', 'Ciudad:', 'label-input-y-75', null), $form->text('codigo_postal', 'Codigo postal:', 'label-input-y-75', null), $form->text('calle', 'Calle:', 'label-input-y-75', null), $form->text('casa_numero', 'Numero de casa:', 'label-input-y-75', null), $form->text('dpto', 'Departamento:', 'label-input-y-75', null), $form->text('piso', 'Piso:', 'label-input-y-75', null)],
                        'Academico' => [$form->text('formacion_academica', 'Formacion academica:', 'label-input-y-75', null), $form->text('anio_ingreso', 'Año de ingreso:', 'label-input-y-75', null)],
                        'Contacto' => [$form->text('email', 'Email:', 'label-input-y-75', null), $form->text('telefono1', 'Telefono 1:', 'label-input-y-75', null), $form->text('telefono2', 'Telefono 2:', 'label-input-y-75', null), $form->text('telefono3', 'Telefono 3:', 'label-input-y-75', null)],
                        'Otros' => [$form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', null)],
                    ]) ?>
            </div>
        </div>
    </div>
@endsection
