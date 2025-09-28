@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVO PROFESOR/A'])
            <div class="perfil__info">

                <p class="info-obligatorios">Los campos marcados con <span style="color:red">*</span> son obligatorios.</p>

                <?= $form->generate(route('admin.profesores.store'), 'post', [
                        'Profesor' => [
                            $form->text('nombre', 'Nombre:*', 'label-input-y-75', null, [
                                'required' => true,
                                'placeholder' => 'Ej: Juan',
                                'maxlength' => 50,
                            ]),
                            $form->text('apellido', 'Apellido:*', 'label-input-y-75', null, [
                                'required' => true,
                                'placeholder' => 'Ej: Pérez',
                                'maxlength' => 30,
                            ]),
                            $form->text('dni', 'DNI:*', 'label-input-y-75', null, [
                                'required' => true,
                                'placeholder' => 'Ej: 12345678',
                                'maxlength' => 10,
                            ]),
                            $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', null, [
                                'placeholder' => 'dd/mm/aaaa',
                            ]),
                            $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', null, [
                                'vacio' => 'Seleccione...',
                                '0' => 'Soltero',
                                '1' => 'Casado',
                                '2' => 'Divorciado',
                                '3' => 'Viudo',
                                '4' => 'Cónyuge',
                                '5' => 'Otro',
                            ]),
                        ],
                        'Dirección' => [
                            $form->text('ciudad', 'Ciudad:', 'label-input-y-75', null, [
                                'placeholder' => 'Ej: 9 de julio',
                                'maxlength' => 30,
                            ]),
                            $form->text('codigo_postal', 'Código postal:', 'label-input-y-75', null, [
                                'placeholder' => 'Ej: 6500',
                                'maxlength' => 10,
                            ]),
                            $form->text('calle', 'Calle:', 'label-input-y-75', null, [
                                'placeholder' => 'Ej: Av. Eva Perón',
                                'maxlength' => 30,
                            ]),
                            $form->text('casa_numero', 'Número de casa:', 'label-input-y-75', null, [
                                'placeholder' => 'Ej: 742',
                                'maxlength' => 4,
                            ]),
                            $form->text('dpto', 'Departamento:', 'label-input-y-75', null, [
                                'placeholder' => 'Ej: A',
                                'maxlength' => 5,
                            ]),
                            $form->text('piso', 'Piso:', 'label-input-y-75', null, [
                                'placeholder' => 'Ej: 3',
                                'maxlength' => 15,
                            ]),
                        ],
                        'Académico' => [
                            $form->text('formacion_academica', 'Formación académica:*', 'label-input-y-75', null, [
                                'required' => true,
                                'placeholder' => 'Ej: Profesorado en Matemática',
                                'maxlength' => 150,
                            ]),
                            $form->text('anio_ingreso', 'Año de ingreso:*', 'label-input-y-75', null, [
                                'required' => true,
                                'placeholder' => 'Ej: 2020',
                                'maxlength' => 4,
                            ]),
                        ],
                        'Contacto' => [
                            $form->text('email', 'Email:*', 'label-input-y-75', null, [
                                'required' => true,
                                'placeholder' => 'ejemplo@dominio.com',
                                'maxlength' => 50,
                            ]),
                            $form->text('telefono_1', 'Teléfono 1:*', 'label-input-y-75', null, [
                                'required' => true,
                                'placeholder' => 'Ej: +54 9 2317-876544',
                                'maxlength' => 30,
                            ]),
                            $form->text('telefono_2', 'Teléfono 2:', 'label-input-y-75', null, [
                                'placeholder' => 'Ej: +54 9 2317-876543',
                                'maxlength' => 30,
                            ]),
                        ],
                        'Otros' => [
                            $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', null, [
                                'placeholder' => 'Notas adicionales sobre el profesor/a',
                                'maxlength' => 150,
                            ]),
                        ],
                    ]) ?>
            </div>
        </div>
    </div>
@endsection
