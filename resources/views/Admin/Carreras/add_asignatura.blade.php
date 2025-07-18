@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Agregar asignatura</h2>
            </div>
            <livewire:asignatura-selector :asignaturas="$asignaturas" :carrera="$carrera"  />
        </div>
    </div>
@endsection
