@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVO ALUMNO/A'])
            <div class="perfil__info">

                <?= $form->generate(route('admin.alumnos.store'), 'post', [
              'Alumno' => [$form->text('nombre', 'Nombre:', 'label-input-y-75', null), $form->text('apellido', 'Apellido:', 'label-input-y-75', null), $form->text('dni', 'DNI:', 'label-input-y-75', null), $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', null, ['inputclass' => 'p-1 w-75p']), $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', null, ['Vacio', 'Soltero', 'Casado', 'Divorciado', 'Viudo', 'Conyuge', 'Otro']), $form->select('genero', 'Género:', 'label-input-y-75', null, ['Vacio', 'Masculino', 'Femenino', 'Otro'])],
              'Dirección' => [$form->text('ciudad', 'Ciudad:', 'label-input-y-75', null), $form->text('codigo_postal', 'Codigo postal:', 'label-input-y-75', null), $form->text('calle', 'Calle:', 'label-input-y-75', null), $form->text('casa_numero', 'Altura:', 'label-input-y-75', null), $form->text('dpto', 'Departamento:', 'label-input-y-75', null), $form->text('piso', 'Piso:', 'label-input-y-75', null)],
              'Contacto' => [$form->text('email', 'Email:', 'label-input-y-75', null), $form->text('telefono1', 'Telefono 1:', 'label-input-y-75', null), $form->text('telefono2', 'Telefono 2:', 'label-input-y-75', null), $form->text('telefono3', 'Telefono 3:', 'label-input-y-75', null)],
              'Academico' => [
                  $form->text('titulo_anterior', 'Titulo anterior:', 'label-input-y-75', null),
                  $form->text('becas', 'Becas:', 'label-input-y-75', null),
                  $form->text('nombre_institucion_secundario', 'Nombre de institucion Secundaria:', 'label-input-y-75', null),
                  $form->select('titulo_secundario', 'Título secundario:', 'label-input-y-75', null, [
                      null => 'Seleccione una opción',
                      0 => 'Fotocopia del título original secundario',
                      1 => ' Certificado de constancia de título en trámite',
                      2 => 'Constancia de alumno del último año del nivel secundario',
                      3 => 'No entregado',
                  ]),
              ],
              'Otros' => [$form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', null)],
          ]) ?>

            </div>
        </div>
    </div>
@endsection
