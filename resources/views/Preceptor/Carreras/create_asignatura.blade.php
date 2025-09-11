@extends('preceptor.template')

@section('content')
    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR NUEVA ASIGNATURA'])
            <div class="perfil__info">
                <form method="post" action="{{route('preceptor.carreras.createAsignatura', ['carrera' => $carrera->id])}}"
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
    </div>
@endsection