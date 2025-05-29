@extends('Admin.template')

@section('content')

    @php
        $ultimaCarreraSeleccionada = null;
        $ultimaAsignaturaSeleccionada = null;


    @endphp

    <div>
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Crear nueva cursada</h2>
            </div>
            <div class="perfil__info">


            <form method="post" action="{{route('admin.cursadas.store')}}">
            @csrf
            <div class="perfil_dataname">
                <label>Carrera:</label>
                <select class="campo_info rounded" name="carrera" id="carrera_select">
                    <option selected >Selecciona una carrera</option>
                    @foreach ($carreras as $carrera)
                        @php
                            if(old('carrera')==$carrera->id){
                                $ultimaCarreraSeleccionada = $carrera;
                            }
                        @endphp
                        <option @selected(old('carrera')==$carrera->id) value="{{$carrera->id}}">{{$carrera->nombre}}</option>
                    @endforeach
                </select>
            </div>
                <div class="perfil_dataname">
                    <label>Materia:</label>
                    <select id="asignatura_select" class="asignatura campo_info rounded" name="id_asignatura">
                        @if ($ultimaCarreraSeleccionada)
                            <option value="{{old('id_asignatura')}}">{{$ultimaCarreraSeleccionada->asignaturas->where('id',old('id_asignatura'))->first()->nombre}}</option>
                        @endif
                        @if (old('carrera') && old('id_asignatura'))
                            <option value="{{old('id_asignatura')}}">Selecciona una carrera</option>
                        @endif

                    </select>
                </div>
                <div class="perfil_dataname">
                    <label>Alumno:</label>
                    <select class="alumno campo_info rounded" name="id_alumno">
                        <option selected>Selecciona un alumno</option>
                        @foreach($alumnos as $alumno)
                            <option @selected(old('id_alumno') == $alumno->id) value="{{$alumno->id}}">{{$alumno->apellidoNombre()}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="perfil_dataname">
                    <label>Año de cursada:</label>
                    <input class="campo_info rounded" value="{{old('anio_cursada')?old('anio_cursada'):$config['anio_remat']}}" placeholder="{{$config['anio_remat']}}" name="anio_cursada">
                </div>
                <div class="perfil_dataname">
                    <label>Condicion:</label>
                    <select class="campo_info rounded" name="condicion">
                        <option @selected(old('condicion') == 1) value="1">Regular</option>
                        //WARN: no eliminar campos "Promocion" y "Equivalencia"
                        <option @selected(old('condicion') == 0) value="0">Libre</option>
                        <option @selected(old('condicion') == 2) value="2">Promocion</option>
                        <option @selected(old('condicion') == 3) value="3">Equivalencia</option>
                        <option @selected(old('condicion') == 4) value="2">Itinerante</option>
                        <option @selected(old('condicion') == 5) value="3">Oyente</option>
                    </select>
                </div>
                <div class="upd"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Crear</button></div>
            </form>
            </div>
        </div>
    </div>
    <script src="{{asset('js/obtener-materias.js')}}"></script>
@endsection
