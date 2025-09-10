@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'AGREGAR ASIGNATURA A CARRERA'])
            <nav aria-label="breadcrumb" class="mb-4">
                <ul class="breadcrumb flex items-center gap-2 text-sm text-gray-700">
                    <li class="flex items-center">
                        <a href="/admin/carreras">Carreras</a>
                    </li>
                    <li>
                        <a href="/admin/carreras/{{ $carrera->id }}/edit">{{ $carrera->nombre }}</a>
                    </li>
                    <li>
                        <span class="text-gray-500" style="color: black;"> Agregar asignatura</span>
                    </li>
                </ul>
            </nav>
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
