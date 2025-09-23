@extends('Admin.template')

@section('content')

    @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE USUARIOS ADMINISTRATIVOS'])

    {{-- FORMULARIO DE CREACIÓN --}}
    <div class="perfil_one br p-5">
        <form method="POST" action="{{ route('admin.admins.store') }}" class="grid grid-cols-2 gap-4">
            @csrf

            {{-- Campo Usuario --}}
            {!! $form->text('username', 'Usuario:', 'label-input-y-100', old('username')) !!}

            {{-- Campo Contraseña --}}
            {!! $form->password('password', 'Contraseña:', 'label-input-y-100', null) !!}

            {{-- Campo Rol --}}
            {!! $form->select('rol', 'Rol:', 'label-input-y-100', old('rol'), [
                'regente' => 'Regente',
                'preceptor' => 'Preceptor',
                'secretario' => 'Secretario',
            ]) !!}
            
            <div class="botones-derecha">
        {{-- @if (isset($mostrar_botones) && $mostrar_botones) --}}
        <x-btn-cancelar />
 {{-- Botón Crear --}}
            <div class="col-span-2 flex justify-end pt-4">
                <button type="submit" class="btn_blue">
                    <i class="ti ti-circle-plus" style="font-size: 1.2em; margin-right: 8px;"></i>Crear usuario
                </button>
            </div>
    </div>
        </form>
    </div>

    {{-- LISTADO DE USUARIOS --}}
    <div class="table br mt-8">
        <table class="table__body">
            <thead>
                <tr>
                    <th class="center">ID</th>
                    <th class="center">Usuario</th>
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
                    <tr>
                        <td class="center">{{ $admin->id }}</td>
                        <td class="center">{{ $admin->username }}</td>
                        <td class="center">
                            @if (!$config['modo_seguro'])
                            <form id="form-eliminar-{{ $admin->id }}"
                                action="{{ route('admin.admins.destroy', ['admin' => $admin->id]) }}"
                                method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="openGeneralModal(
                                        'form-eliminar-{{ $admin->id }}',
                                        `¿Estás seguro de que querés eliminar al usuario: {{ strtoupper($admin->apellido ?? '') }} {{ strtoupper($admin->nombre ?? '') }}?\n\nRol asignado: {{ $nombresRol[$admin->rol] ?? 'Sin rol definido' }}\n\nESTA ACCIÓN NO SE PUEDE DESHACER.`
                                    )"
                                    class="btn_icon-danger"
                                    style="background-color: red; margin-left: 10px;">
                                    <i class="ti ti-trash" style="font-size: 1.3em"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="w-full flex justify-center py-6">
        {{ $admins->appends(request()->query())->links('Componentes.pagination') }}
    </div>

@endsection
