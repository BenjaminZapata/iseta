@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Agregar asignatura</h2>
            </div>
            <div class="perfil__info">
                <form method="post" action="{{route('admin.carreras.add.post')}}">
                @csrf

                    <div class="perfil_dataname">
                        <label>Asignatura:</label>
                        <select class="campo_info rounded" name="id_asignatura">
                            @foreach($asignaturas as $asignatura)
                                <option @selected($id_asignatura==$asignatura->id) value="{{$asignatura->id}}">
                                    {{$asignatura->nombre}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Carrera:&nbsp;</label>
                        {{$carrera->nombre}}
                    </div>
                    <div class="perfil_dataname">
                        <label>Tipo modulo:</label>
                        <select class="campo_info rounded" name="tipo_modulo">
                            <option @selected($asignatura->tipo_modulo==1) value="1">Modulos</option>
                            <option @selected($asignatura->tipo_modulo==2) value="2">Horas</option>
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Carga horaria:</label>
                        <input class="campo_info rounded" value="{{$asignatura->carga_horaria}}" name="carga_horaria">
                    </div>
                    <div class="perfil_dataname">
                        <label>Año:</label>
                        <input class="campo_info rounded"  name="anio">
                    </div>
                    <div class="upd"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection
