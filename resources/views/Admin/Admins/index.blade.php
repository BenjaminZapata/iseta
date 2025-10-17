@extends('Admin.template')

@section('content')

@include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE USUARIOS ADMINISTRATIVOS'])

{{-- FILTROS --}}
<div class="perfil__header-alt" style="display: flex; align-items: center; gap: 1rem;">
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

{{-- FORMULARIO CREAR ADMIN --}}
<div class="perfil_one br p-5">
    <form method="POST" action="{{ route('admin.admins.store') }}" class="grid grid-cols-2 gap-4">
        @csrf
        {!! $form->text('username', 'Usuario:', 'label-input-y-100', old('username')) !!}
        {!! $form->password('password', 'Contraseña:', 'label-input-y-100', old('password')) !!}
        {!! $form->select('rol', 'Rol:', 'label-input-y-100', old('rol'), [
            'regente' => 'Regente',
            'preceptor' => 'Preceptor',
            'secretario' => 'Secretario',
        ]) !!}
        {!! $form->text('email', 'Email:', 'label-input-y-100', old('email')) !!}

        <div class="col-span-2 flex justify-end pt-4 gap-2">
            <x-btn-cancelar />
            <button type="submit" class="btn_blue">
                <i class="ti ti-circle-plus" style="font-size: 1.2em; margin-right: 8px;"></i>Crear usuario
            </button>
        </div>
    </form>
</div>
{{-- Botón eliminar seleccionados --}}
<form id="form-eliminar-seleccionados" action="{{ route('admin.admins.eliminarMasivo') }}" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="ids" id="ids-seleccionados" value="">
    <button type="button" id="btn-eliminar-seleccionados" class="btn_red">
        <i class="ti ti-trash"></i> Eliminar seleccionados
    </button>
</form>


{{-- TABLA ADMIN --}}
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
                    <td class="center">
                        {{-- Botones individuales --}}
                        <button type="button"
                            class="btn_blue btn-modificar"
                            style="font-size: 1em; margin-right: 5px;">
                            <i class="ti ti-pencil" style="font-size: 1.3em; margin-right: 8px;"></i>Modificar
                        </button>

                        <button type="button"
                            class="btn_blue btn-guardar"
                            style="background-color: green; font-size: 1em; margin-right: 5px; display:none;"
                            id="guardar-{{ $admin->id }}">
                            <i class="ti ti-check" style="font-size: 1.3em; margin-right: 8px;"></i>Guardar
                        </button>

                        <button type="button"
                            class="btn_blue btn-cancelar"
                            style="background-color: gray; font-size: 1em; margin-right: 5px; display:none;"
                            id="cancelar-{{ $admin->id }}">
                            <i class="ti ti-x" style="font-size: 1.3em; margin-right: 8px;"></i>Cancelar
                        </button>

                        <form id="form-eliminar-{{ $admin->id }}"
                            action="{{ route('admin.admins.destroy', ['admin' => $admin->id]) }}"
                            method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                onclick="openGeneralModal(
                                    'form-eliminar-{{ $admin->id }}',
                                    `¿Estás seguro de que querés eliminar al usuario: {{ strtoupper($admin->username) }}?\nRol asignado: {{ $nombresRol[$admin->rol] }}\nESTA ACCIÓN NO SE PUEDE DESHACER.`)"
                                class="btn_icon-danger" style="background-color: red;">
                                <i class="ti ti-trash" style="font-size: 1.3em"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- PAGINACION --}}
<div class="w-full flex justify-center py-6">
    {{ $admins->appends(request()->query())->links('Componentes.pagination') }}
</div>

@endsection

@section('scripts')
    {{-- JS de modificar usuarios --}}
    <script src="{{ asset('js/usuarios/ModificarUsuarios.js') }}?v={{ time() }}"></script>

    {{-- JS independiente solo para eliminar múltiples --}}
    <script src="{{ asset('js/usuarios/EliminarAdmins.js') }}?v={{ time() }}"></script>
@endsection
