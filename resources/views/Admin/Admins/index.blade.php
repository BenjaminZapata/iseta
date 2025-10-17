@extends('Admin.template')

@section('content')

@include('components.header-avatar', ['tituloSeccion' => 'GESTIÓN DE USUARIOS ADMINISTRATIVOS'])

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

<div class="table br mt-2">
    <table class="table__body">
        <thead>
            <tr>
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
                    <td class="center rol-text">{{ $nombresRol[$admin->rol] }}</td>
                    <td class="center username-text">{{ $admin->username }}</td>
                    <td class="center email-text">{{ $admin->email }}</td>
                    <td class="center password-text">****</td>
                    <td class="center">
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

<div class="w-full flex justify-center py-6">
    {{ $admins->appends(request()->query())->links('Componentes.pagination') }}
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.btn-modificar').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const fila = e.target.closest('tr');
            editarFila(fila);
        });
    });

    document.querySelectorAll('.btn-guardar').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const fila = e.target.closest('tr');
            guardarFila(fila);
        });
    });

    document.querySelectorAll('.btn-cancelar').forEach(btn => {
        btn.addEventListener('click', () => location.reload());
    });

});

function editarFila(fila) {
    const id = fila.dataset.id;
    const username = fila.dataset.username;
    const rol = fila.dataset.rol;
    const email = fila.dataset.email;

    fila.querySelector('.username-text').innerHTML = `<input type="text" id="input-username-${id}" value="${username}" class="w-full">`;
    fila.querySelector('.email-text').innerHTML = `<input type="email" id="input-email-${id}" value="${email}" class="w-full">`;
    fila.querySelector('.password-text').innerHTML = `<input type="password" id="input-password-${id}" placeholder="Ingrese nueva contraseña si quiere cambiarla" class="w-full">`;

    const rolOptions = {0:'Regente',1:'Preceptor',2:'Secretario'};
    let selectHTML = `<select id="input-rol-${id}" class="w-full">`;
    for(const key in rolOptions){
        selectHTML += `<option value="${key}" ${key == rol ? 'selected' : ''}>${rolOptions[key]}</option>`;
    }
    selectHTML += `</select>`;
    fila.querySelector('.rol-text').innerHTML = selectHTML;

    fila.querySelector('.btn-modificar').style.display = 'none';
    fila.querySelector('.btn-guardar').style.display = 'inline-block';
    fila.querySelector('.btn-cancelar').style.display = 'inline-block';
}

function guardarFila(fila) {
    const id = fila.dataset.id;
    const username = document.getElementById(`input-username-${id}`).value;
    const email = document.getElementById(`input-email-${id}`).value;
    const rol = document.getElementById(`input-rol-${id}`).value;
    const password = document.getElementById(`input-password-${id}`).value;

    fetch(`{{ url('admin/admins') }}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ username, email, rol, password })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error al actualizar el usuario');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error al actualizar el usuario');
    });
}
</script>

@endsection
