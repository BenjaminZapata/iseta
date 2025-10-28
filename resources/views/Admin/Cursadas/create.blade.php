@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVA CURSADA'])
            <div class="perfil__info">
                <livewire:registrar-cursada />
            </div>
        </div>
    </div>
@endsection