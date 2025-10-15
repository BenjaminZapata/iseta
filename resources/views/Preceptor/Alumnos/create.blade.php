@extends('preceptor.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('preceptor.header-avatar', ['tituloSeccion' => 'CREAR NUEVO ALUMNO/A'])
            <div class="perfil__info">
                <livewire:create-alumno />
            </div>
        </div>
    </div>
@endsection