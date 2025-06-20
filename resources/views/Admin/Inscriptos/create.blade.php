@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Crear nuevo inscripto</h2>
            </div>
            <div class="perfil__info">
                <form method="post" action="{{route('admin.inscriptos.store')}}">
                @csrf

                    <div class="perfil_dataname">
                        <label>Alumno:</label>
                        <select class="campo_info rounded" name="id_alumno">
                            <option value="">Selecciona un alumno</option>
                            @foreach ($alumnos as $alumno)
                            <option value="{{$alumno->id}}">{{$alumno->apellidoNombre()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Carrera:</label>
                        <select class="campo_info rounded" name="id_carrera">
                            @foreach ($carreras as $carrera)
                            <option value="{{$carrera->id}}">{{$carrera->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Año inscripcion:</label>
                        <input class="campo_info rounded"  name="anio_inscripcion">
                    </div>
                    <div class="perfil_dataname">
                        <label>Indice libro matriz:</label>
                        <input class="campo_info rounded" name="indice_libro_matriz">
                    </div>
                    <div class="perfil_dataname">
                        <label>Año finalizacion:</label>
                        <input class="campo_info rounded" name="anio_finalizacion">
                    </div>
                        </div>
                    <div class="perfil_dataname">
                        <label>Estado:</label>
                        <input class="campo_info rounded" type="text" name="estado_texto" value="Cursando" readonly>
                        <input type="hidden" name="estado" value="0">
                    </div>

                    <input name="redirect" type="hidden" value="{{url()->previous()}}">
                    <div class="upd"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Crear</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection
