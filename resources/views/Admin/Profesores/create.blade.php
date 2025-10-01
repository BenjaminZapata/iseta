@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVO PROFESOR/A'])
            <div class="perfil__info">

                <form action="{{ route('admin.profesores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <?= $form->generate(null, 'post', [
                            'Profesor' => [
                                $form->text('nombre', 'Nombre:*', 'label-input-y-75', old('nombre'), [
                                    'placeholder' => 'Ej: Juan',
                                    'maxlength' => 50,
                                ]),
                                $form->text('apellido', 'Apellido:*', 'label-input-y-75', old('apellido'), [
                                    'placeholder' => 'Ej: Pérez',
                                    'maxlength' => 30,
                                ]),
                                $form->text('dni', 'DNI:*', 'label-input-y-75', old('dni'), [
                                    'placeholder' => 'Ej: 12345678',
                                    'maxlength' => 10,
                                ]),
                                $form->date('fecha_nacimiento', 'Fecha de nacimiento:', 'label-input-y-75', old('fecha_nacimiento'), [
                                    'placeholder' => 'dd/mm/aaaa',
                                ]),
                                $form->select('estado_civil', 'Estado civil:', 'label-input-y-75', old('estado_civil'), [
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
                                $form->text('ciudad', 'Ciudad:', 'label-input-y-75', old('ciudad'), [
                                    'placeholder' => 'Ej: 9 de julio',
                                    'maxlength' => 30,
                                ]),
                                $form->text('codigo_postal', 'Código postal:', 'label-input-y-75', old('codigo_postal'), [
                                    'placeholder' => 'Ej: 6500',
                                    'maxlength' => 10,
                                ]),
                                $form->text('calle', 'Calle:', 'label-input-y-75', old('calle'), [
                                    'placeholder' => 'Ej: Av. Eva Perón',
                                    'maxlength' => 30,
                                ]),
                                $form->text('casa_numero', 'Número de casa:', 'label-input-y-75', old('casa_numero'), [
                                    'placeholder' => 'Ej: 742',
                                    'maxlength' => 4,
                                ]),
                                $form->text('dpto', 'Dpto:', 'label-input-y-75', old('dpto'), [
                                    'placeholder' => 'Ej: A',
                                    'maxlength' => 5,
                                ]),
                                $form->text('piso', 'Piso:', 'label-input-y-75', old('piso'), [
                                    'placeholder' => 'Ej: 3',
                                    'maxlength' => 15,
                                ]),
                            ],
                            'Académico' => [
                                $form->text('formacion_academica', 'Formación académica:*', 'label-input-y-75', old('formacion_academica'), [
                                    'placeholder' => 'Ej: Profesorado en Matemática',
                                    'maxlength' => 150,
                                ]),
                                $form->text('anio_ingreso', 'Año de ingreso:*', 'label-input-y-75', old('anio_ingreso'), [
                                    'placeholder' => 'Ej: 2020',
                                    'maxlength' => 4,
                                ]),
                            ],
                            'Contacto' => [
                                $form->text('email', 'Email:*', 'label-input-y-75', old('email'), [
                                    'placeholder' => 'ejemplo@dominio.com',
                                    'maxlength' => 50,
                                ]),
                                $form->text('telefono_1', 'Teléfono 1:*', 'label-input-y-75', old('telefono_1'), [
                                    'placeholder' => 'Ej: 2317-876544',
                                    'maxlength' => 30,
                                ]),
                                $form->text('telefono_2', 'Teléfono 2:', 'label-input-y-75', old('telefono_2'), [
                                    'placeholder' => 'Ej: 2317-876543',
                                    'maxlength' => 30,
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
