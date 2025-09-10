@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'AGREGAR ASIGNATURA A CARRERA'])
            <livewire:asignatura-selector :asignaturas="$asignaturas" :carrera="$carrera" />
             <div class="botones-derecha"
                            style="margin-right: 27px; padding-top: 10px; padding-bottom: 16px; display: flex; gap: 12px; justify-content: flex-end;">
                            <x-botones-alumno />
                            <x-btn-cancelar />
                <button type="submit" class="btn_blue">
                    <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                       Crear
                 </button>
            </div>
        </div>
    </div>
@endsection