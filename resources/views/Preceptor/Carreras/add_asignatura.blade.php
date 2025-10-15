@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('preceptor.header-avatar', ['tituloSeccion' => 'AGREGAR ASIGNATURA A CARRERA'])
            <livewire:asignatura-selector :asignaturas="$asignaturas" :carrera="$carrera" />
        </div>
    </div>
@endsection