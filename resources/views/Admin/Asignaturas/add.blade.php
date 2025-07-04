@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Agregar asignatura</h2>
            </div>
            <div class="perfil__info">
                <form method="post" action="{{route('admin.asignaturas.store')}}">
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
                        <label>Carrera:</label>
                        <select class="campo_info rounded" name="id_carrera">
                            @foreach($carreras as $carrera)
                                <option @selected($id_carrera==$carrera->id) value="{{$carrera->id}}">
                                    {{$carrera->nombre}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="upd"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection
