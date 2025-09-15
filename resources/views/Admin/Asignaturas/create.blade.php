@extends('Admin.template')

@section('content')
<div>
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'CREAR ASIGNATURA'])
        <div class="perfil__header">
            <h2>Crear asignatura</h2>
        </div>
        <div class="perfil__info">
            <form method="post" action="{{ route('admin.asignaturas.store') }}">
                @csrf

                {{-- Nombre --}}
                <div class="perfil_dataname">
                    <label>Asignatura:</label>
                    <input class="campo_info rounded" 
                           name="nombre" 
                           value="{{ old('nombre') }}">
                    @error('nombre')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Tipo módulo --}}
                <div class="perfil_dataname">
                    <label>Tipo módulo:</label>
                    <select class="campo_info rounded" name="tipo_modulo">
                        <option value="">-- Seleccione --</option>
                        <option value="1" {{ old('tipo_modulo') == 1 ? 'selected' : '' }}>Módulos</option>
                        <option value="2" {{ old('tipo_modulo') == 2 ? 'selected' : '' }}>Horas</option>
                    </select>
                    @error('tipo_modulo')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Carga horaria --}}
                <div class="perfil_dataname">
                    <label>Carga horaria:</label>
                    <input class="campo_info rounded" 
                           name="carga_horaria" 
                           value="{{ old('carga_horaria') }}">
                    @error('carga_horaria')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Año --}}
                <div class="perfil_dataname">
                    <label>Año:</label>
                    <input class="campo_info rounded" 
                           name="anio" 
                           value="{{ old('anio') }}">
                    @error('anio')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Observaciones --}}
                <div class="perfil_dataname">
                    <label>Observaciones:</label>
                    <input class="campo_info rounded" 
                           name="observaciones" 
                           value="{{ old('observaciones') }}">
                    @error('observaciones')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="botones-derecha">
                    <x-botones-alumno />
                    <x-btn-cancelar />
                    <button type="submit" class="btn_blue">
                        <i class="ti ti-user-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
