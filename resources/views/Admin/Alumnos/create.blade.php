@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Crear nuevo alumno/a</h2>
            </div>
            <div class="perfil__info">

    <?= $form->generate(route('admin.alumnos.store'),'post',[
        'Alumno' => [
            $form->text('nombre','Nombre:','label-input-y-75',null),
            $form->text('apellido','Apellido:','label-input-y-75',null),
            $form->text('dni','DNI:','label-input-y-75',null),
            $form->date('fecha_nacimiento','Fecha de nacimiento:','label-input-y-75',null,['inputclass'=>'p-1 w-75p']),
            $form->select('estado_civil','Estado civil:','label-input-y-75',null,['Vacio','Soltero','Casado','Divorciado','Viudo','Conyuge','Otro']),

        ],
        'Dirección' => [
            $form->text('ciudad','Ciudad:','label-input-y-75',null),
                $form->text('codigo_postal','Codigo postal:','label-input-y-75',null),
                $form->text('calle','Calle:','label-input-y-75',null),
                $form->text('casa_numero','Altura:','label-input-y-75',null),
                $form->text('dpto','Departamento:','label-input-y-75',null),
                $form->text('piso','Piso:','label-input-y-75',null)
        ],
        'Contacto' => [
            $form->text('email','Email:','label-input-y-75',null),
            $form->text('telefono1','Telefono 1:','label-input-y-75',null),
            $form->text('telefono2','Telefono 2:','label-input-y-75',null),
            $form->text('telefono3','Telefono 3:','label-input-y-75',null)
        ],
        'Academico' => [
            $form->text('titulo_anterior','Titulo anterior:','label-input-y-75',null),
            $form->text('becas','Becas:','label-input-y-75',null),
            $form->text('nombre_institucion_secundario','Secundaria:','label-input-y-75',null),
            $form->select('titulo','Titulo secundario:','label-input-y-75',null,
            ['vacio',
            'Fotocopia del título original secundario',
            'Certificado de constancia de título en trámite',
            'Constancia de alumno del último año del nivel secundario',
            'No entregado'])
        ],
        'Otros' => [$form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', null)]
    ]) ?>
           
            </div>
        </div>
    </div>
@endsection
