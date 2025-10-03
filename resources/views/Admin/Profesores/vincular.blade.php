@extends('Admin.template')

@section('content')
<div class="edit-form-container">
 <div class="perfil_one br">

  {{-- HEADER --}}
  @include('components.header-avatar', ['tituloSeccion' => 'VINCULAR ASIGNATURAS'])

  <div class="perfil__info">
   <h3 class="mb-3">Profesor: {{ $profesor->apellido }}, {{ $profesor->nombre }}</h3>

   <form method="POST" action="{{ route('admin.profesores.vinculaciones', $profesor) }}">
    @csrf

    {{-- Componente de selección de carreras y asignaturas --}}
    @include('components.vinculacion-profesor', [
    'carreras' => $carreras,
    'profesor' => $profesor
    ])

    <div class="mt-4">
     <button type="submit" class="btn btn-primary">Guardar vinculaciones</button>
     <a href="{{ route('admin.profesores.vinculaciones', $profesor) }}" class="btn btn-secondary">Cancelar</a>
    </div>
   </form>
  </div>
 </div>
</div>
@endsection