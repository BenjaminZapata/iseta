@extends('Admin.template')

@section('content')
<div>
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVA CURSADA'])
        <div class="perfil__info">
            <livewire:registrar-cursada />
            <div class="botones-derecha mt-3 d-flex justify-content-end gap-2">
                <x-btn-cancelar :url="route('admin.cursadas.index')" />
            </div>

        </div>
    </div>
</div>
@endsection