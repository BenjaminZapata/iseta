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
                @foreach ($admins as $admin)
                    <tr>
                        <td class="center">{{$admin->id}}</td>
                        <td>{{$admin->username}}</td>
                        {{---<td><a href="{{route('admin.admins.edit', ['admin' => $admin->id])}}"><button
                                    class="btn_edit">Editar</button></a></td>---}}
                        @if (!$config['modo_seguro'])
                            <td class="center">
                                <form method="POST" class="form-eliminar"
                                    action="{{route('admin.admins.destroy', ['admin' => $admin->id])}}">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" value="Eliminar" class="btn_borrar-alt">
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