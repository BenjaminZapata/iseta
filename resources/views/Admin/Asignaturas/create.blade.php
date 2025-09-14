@extends('Admin.template')


@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR ASIGNATURA'])
            <div class="perfil__info">
                <form method="post" action="{{ route('admin.asignaturas.store') }}">
                    @csrf
                    <div class="perfil_dataname">
                        <label>Asignatura:</label>
                        <input class="campo_info_exa rounded" name="nombre">
                    </div>
                    <div class="perfil_dataname">
                        <label>Tipo modulo:</label>
                        <select class="campo_info rounded" name="tipo_modulo">
                            <option value="1">Modulos</option>
                            <option value="2">Horas</option>
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Carga horaria:</label>
                        <input class="campo_info_exa rounded" name="carga_horaria">
                    </div>
                    <div class="perfil_dataname">
                        <label>Año:</label>
                        <input class="campo_info_exa rounded" name="anio">
                    </div>
                    <div class="perfil_dataname">
                        <label>Observaciones:</label>
                        <input class="campo_info_exa rounded" name="observaciones">
                    </div>

                    <div class="botones-derecha">

                        <x-botones-alumno />
                        {{-- @if (isset($mostrar_botones) && $mostrar_botones) --}}
                        <x-btn-cancelar />
                        <button type="submit" class="btn_blue">
                            <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                            Guardar

                        </button>
                </form>
            </div>
        </div>
    </div>
@endsection
