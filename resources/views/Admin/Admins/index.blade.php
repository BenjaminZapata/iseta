@extends('Admin.template')

@section('content')

<div class="perfil_one br">
    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE USUARIOS ADMINISTRATIVOS'])

    {{-- FORMULARIO CREAR ADMIN --}}
    <div class="perfil_one br p-5">
        <?= $form->generate(route('admin.admins.store'), 'post', [
            'Nuevo administrador' => [
                $form->text('username', 'Usuario:', 'label-input-y-100', old('username'), [
                    'placeholder' => 'Ej: adminrp'
                ]),
                $form->password('password', 'Contraseña:', 'label-input-y-100', old('password'), [
                    'placeholder' => 'Mínimo 8 caracteres'
                ]),
                $form->select('rol', 'Rol del usuario:', 'label-input-y-75', old('rol'), [
                    " " => 'Seleccione un rol',
                    'regente' => 'Regente',
                    'preceptor' => 'Preceptor',
                    'secretario' => 'Secretario',
                ]),
                $form->text('email', 'Email:', 'label-input-y-100', old('email'), [
                    'placeholder' => 'Ej: admin@instituto.edu.ar'
                ]),
            ]
        ]) ?>
    </div>

    {{-- BLOQUE: TABLA DE ADMINISTRADORES --}}
    <div class="perfil_one br mt-4">
        <div class="perfil__header">
            <h2>Tabla de administradores</h2>
        </div>

        <div class="perfil__info">
            {{-- FILTROS Y BOTÓN ELIMINAR --}}
            <div class="perfil__header-alt d-flex align-items-center gap-4">
                {{-- BOTÓN ELIMINAR SELECCIONADOS --}}
                @if (!$config['modo_seguro'])
                    <form id="form-eliminar-seleccionados" action="{{ route('admin.admins.eliminarMasivo') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="ids-seleccionados" value="">
                        <button type="button" id="btn-eliminar-seleccionados" class="btn_red">
                            <i class="ti ti-trash"></i> Eliminar seleccionados
                        </button>
                    </form>
                @endif

                <?= $filtergen->generate('admin.admins.index', $filters, [
                    'dropdowns' => [
                        $form->select('rol', 'rol:', 'label-input-y-100', old('rol', $filters->rol ?? ''), [
                            '' => 'Todos',
                            0 => 'Regente',
                            1 => 'Preceptor',
                            2 => 'Secretario',
                        ]),
                    ],
                    'fields' => [
                        'username' => 'Usuario',
                        'email' => 'Email',
                    ],
                ]) ?>
            </div>

            {{-- TABLA --}}
            <div class="table br mt-2">
                <table class="table__body">
                    <thead>
                        <tr>
                            <th class="center"><input type="checkbox" id="check-todos"></th>
                            <th class="center">Rol</th>
                            <th class="center">Usuario</th>
                            <th class="center">Email</th>
                            <th class="center">Contraseña</th>
                            <th class="center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nombresRol = [
                                0 => 'Regente',
                                1 => 'Preceptor',
                                2 => 'Secretario',
                            ];
                        @endphp

                        @foreach ($admins as $admin)
                            <tr id="fila-{{ $admin->id }}"
                                data-id="{{ $admin->id }}"
                                data-username="{{ $admin->username }}"
                                data-rol="{{ $admin->rol }}"
                                data-email="{{ $admin->email }}">

                                <td class="center">
                                    <input type="checkbox" class="check-admin" value="{{ $admin->id }}">
                                </td>
                                <td class="center rol-text">{{ $nombresRol[$admin->rol] }}</td>
                                <td class="center username-text">{{ $admin->username }}</td>
                                <td class="center email-text">{{ $admin->email }}</td>
                                <td class="center password-text">****</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <div style="display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                        {{-- Botón Modificar --}}
                                        <button type="button"
                                                class="btn_blue btn-modificar"
                                                style="font-size: 0.95em; min-width: 120px;">
                                            <i class="ti ti-pencil" style="font-size: 1.2em; margin-right: 6px;"></i>
                                            Modificar
                                        </button>

                                        {{-- Botón Guardar --}}
                                        <button type="button"
                                                class="btn_blue btn-guardar"
                                                style="background-color: green; font-size: 0.95em; display: none; min-width: 120px;"
                                                id="guardar-{{ $admin->id }}">
                                            <i class="ti ti-check" style="font-size: 1.2em; margin-right: 6px;"></i>
                                            Guardar
                                        </button>

                                        {{-- Botón Cancelar --}}
                                        <button type="button"
                                                class="btn_blue btn-cancelar"
                                                style="background-color: gray; font-size: 0.95em; display: none; min-width: 120px;"
                                                id="cancelar-{{ $admin->id }}">
                                            <i class="ti ti-x" style="font-size: 1.2em; margin-right: 6px;"></i>
                                            Cancelar
                                        </button>

                                        {{-- Botón Eliminar --}}
                                        @if (!$config['modo_seguro'])
                                            <form id="form-eliminar-{{ $admin->id }}"
                                                  action="{{ route('admin.admins.destroy', ['admin' => $admin->id]) }}"
                                                  method="POST"
                                                  style="margin: 0; display: inline-flex;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="openGeneralModal(
                                                            'form-eliminar-{{ $admin->id }}',
                                                            `¿Estás seguro de que querés eliminar al usuario: {{ strtoupper($admin->username) }}?\nRol asignado: {{ $nombresRol[$admin->rol] }}\nESTA ACCIÓN NO SE PUEDE DESHACER.`)"
                                                        class="btn_icon-danger"
                                                        style="background-color: red; width: 42px; height: 42px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="ti ti-trash" style="font-size: 1.2em;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    {{-- JS de modificar usuarios --}}
    <script src="{{ asset('js/usuarios/ModificarUsuarios.js') }}?v={{ time() }}"></script>

    {{-- JS independiente solo para eliminar múltiples --}}
    <script src="{{ asset('js/usuarios/EliminarAdmins.js') }}?v={{ time() }}"></script>
@endsection