@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR ASIGNATURA'])

            <div class="perfil__info">
                <form method="post" action="{{ route('admin.asignaturas.store') }}">
                    @csrf

                    {{-- Nombre --}}
                    <div class="perfil_dataname">
                        <label>Asignatura:</label>
                        <input class="campo_info rounded" name="nombre" value="{{ old('nombre') }}">

                    </div>


                    {{-- Carga horaria --}}
                    <div class="perfil_dataname">
                        <label>Cantidad de modulos:</label>
                        <input class="campo_info rounded" name="carga_horaria" value="{{ old('carga_horaria') }}">

                    </div>

                    {{-- Observaciones --}}
                    <div class="perfil_dataname">
                        <label>Observaciones:</label>
                        <input class="campo_info rounded" name="observaciones" value="{{ old('observaciones') }}">
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
