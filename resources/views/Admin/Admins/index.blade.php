@extends('Admin.template')

@section('content')

    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE USUARIOS ADMINISTRATIVOS'])
        <div class="perfil__info">
            <form class="flex-col gap-3" method="POST" action="{{route('admin.admins.store')}}">
                @csrf
                <div class="grid-2 gap-1">

                    <div>
                        <?= $form->text('username', 'Usuario:', 'label-input-y-75', null) ?>
                    </div>
                    <div>
                        <?= $form->password('password', 'Contraseña:', 'label-input-y-75', null) ?>
                    </div>
                    <div>
                        <?= $form->select('rol', 'Rol:', 'label-input-y-75', null, [
        'regente' => 'Regente',
        'preceptor' => 'Preceptor',
        'secretario' => 'Secretario',
    ]) ?>


                    </div>
                    <div class="flex">
                        <input type="submit" value="Crear" class="btn_borrar">
                    </div>
            </form>
        </div>
    </div>

    <div class="table br">
    <table class="table__body">
        <thead>
            <tr>
                <th class="center">Id</th>
                <th>Usuario</th>
                @if (!$config['modo_seguro'])
                    <th class="center">Acción</th>
                @endif
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
                    <td>{{ $admin->username }}</td>

                    @if (!$config['modo_seguro'])
                        <td class="center">
                            <form id="form-eliminar-{{ $admin->id }}"
                                action="{{ route('admin.admins.destroy', ['admin' => $admin->id]) }}"
                                method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="openGeneralModal('form-eliminar-{{ $admin->id }}',
                                        '¿Estás seguro de que querés eliminar al usuario: {{ strtoupper($admin->apellido) }} {{ strtoupper($admin->nombre) }}?\n\nRol asignado: {{ $nombresRol[$admin->rol] ?? 'Sin rol definido' }}\n\nESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                    class="btn_icon-danger"
                                    style="background-color: red; margin-left: 10px;">
                                    <i class="ti ti-trash" style="font-size: 1.3em"></i>
                                </button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


    <div class="w-1/2 mx-auto p-5">
        {{ $admins->appends(request()->query())->links() }}
    </div>



@endsection
