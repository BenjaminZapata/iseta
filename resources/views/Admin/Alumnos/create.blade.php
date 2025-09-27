@extends('Admin.template')

<link rel="stylesheet" href="{{ asset('css/Admin/Alumno/createAlumno.css') }}">

@section('content')
<div>
  <div class="perfil_one br">
    @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVO ALUMNO/A'])
    <div class="perfil__info">
      <livewire:create-alumno />
    </div>
  </div>
</div>
@endsection