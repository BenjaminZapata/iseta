@extends('Admin.template')

@section('content')
  <div>
    <div class="perfil_one br">
    @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVO ALUMNO/A'])
      <div class="perfil__info">
        <livewire:alumno-form/>
      </div>
    </div>
  </div>
@endsection
