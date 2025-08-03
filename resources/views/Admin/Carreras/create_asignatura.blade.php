@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVA ASIGNATURA'])
            <div class="perfil__info">
                <form method="post" action="{{route('Regente.carreras.createAsignatura', ['carrera' => $carrera->id])}}"
                    id="create_asignatura">
                    @csrf
                    <div class="perfil_dataname">
                        <label>Carrera:</label>
                        <p class="campo_info-noinput rounded"> {{ $carrera->nombre }} </p>
                        <input type="hidden" name="carrera_id" value="{{ $carrera->id }}">
                    </div>
                    <div class="perfil_dataname">
                        <label>Asignatura:</label>
                        <input class="campo_info rounded" name="nombre">
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
                        <input class="campo_info rounded" name="carga_horaria">
                    </div>
                    <div class="perfil_dataname">
                        <label>Año:</label>
                        <input class="campo_info rounded" name="anio">
                    </div>
                    <div class="perfil_dataname">
                        <label>Observaciones:</label>
                        <input class="campo_info rounded" name="observaciones">
                    </div>

                    <div class="upd"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Crear</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection