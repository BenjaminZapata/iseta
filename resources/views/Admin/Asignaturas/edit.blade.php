@extends('Admin.template')

@section('content')
<div class="edit-form-container">
    <div class="perfil_one br">
        <div class="perfil__header">
            <h2>Asignatura</h2>
        </div>
        <div class="perfil__info">
            <form method="post" action="{{route('admin.asignaturas.update', ['asignatura'=>$asignatura->id])}}">
            @csrf
            @method('put')

                <div class="perfil_dataname">
                    <label>Asignatura:</label>
                    <input class="campo_info rounded" value="{{$asignatura->nombre}}" name="nombre">
                </div>
                <div class="perfil_dataname">
                    <label>Carrera:</label>
                    <span class="campo_info2">{{$asignatura->carrera->first()?->nombre}}</span>
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
                    <input class="campo_info rounded" value="{{$asignatura->anio}}" name="anio">
                </div>
                <div class="perfil_dataname">
                    <label>Observaciones:</label>
                    <input class="campo_info rounded" value="{{$asignatura->observaciones}}" name="observaciones">
                </div>
                <input type="hidden" value="{{url()->previous()}}" name="redirect">

                <div class="upd"><button class="btn_blue"><i class="ti ti-refresh"></i>Actualizar</button></div>
            </form>
        </div>
    </div>


        {{-- -------------------------- --}}

        @if ($asignatura->anio > 0)
        <div class="perfil_one br">

            <div class="perfil__header">
                <h2>Correlativas</h2>
            </div>

            <div class="matricular">

                @foreach ($asignatura->correlativas as $correlativa)
                <div class="flex items-center"><li>{{$correlativa->asignatura->nombre}}</li></div>
                @if (!$config['modo_seguro'])

                    <form method="post" class="form-eliminar" action="{{route('correlativa.eliminar', ['asignatura'=>$asignatura->id,'asignatura_correlativa'=>$correlativa->asignatura->id])}}">
                        <div class="flex">
                        @csrf
                        @method('delete')

                            <div class="flex items-center m-1 mx-4"><button class="btn_red"><i class="ti ti-backspace"></i>Quitar</button></div>
                        </div>
                    </form>
                @endif
                @endforeach
                    <br><br>
                <form method="post" action="{{route('correlativa.agregar', ['asignatura'=>$asignatura->id])}}">
                @csrf

                    <div class="perfil_dataname1">
                        <label>Materia:</label>
                        <select class="campo_info rounded" id="asignatura_select" name="id_asignatura">
                            @foreach ($asignatura->carrera->first()->asignaturas->where('anio', '<=', $asignatura->anio) as $asignatura_carrera)
                                @if ($asignatura_carrera->id != $asignatura->id)
                                    <option value="{{$asignatura_carrera->id}}">{{$asignatura_carrera->nombre}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="upd"><a href=""><button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar</button></a></div>
                </form>
            </div>
        </div>
        @endif


    <div class="table">
        <div class="perfil__header-alt just-between">
            <p>Alumnos que cursan esta materia que aun no tienen un estado final (aprobado o desaprobado)</p>
            <a class="btn_blue" href="/admin/cursantes/{{$asignatura->id}}"><i class="ti ti-file-download"></i>Exportar cursadas</a>
        </div>
        <table class="table__body">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Condicion</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($asignatura->cursadas as $cursada)
                <tr>
                    <td> {{$cursada->alumno->apellidoNombre()}}</td>
                    <td> {{$cursada->alumno->dni}} </td>
                    <td> {{$cursada->condicionString()}} </td>
                    <td>
                        <a href="{{route('admin.cursadas.edit', ['cursada' => $cursada->id])}}"><button class="btn_blue"><i class="ti ti-edit"></i>Editar</button></a>
                    </td>
                </tr>
            @endforeach


        </tbody>
    </table>
    </div>
    </div>

    @if (!$config['modo_seguro'])
        <div class="upd">
            <form method="POST" class="form-eliminar" action="{{route('admin.asignaturas.destroy', ['asignatura'=>$asignatura->id])}}">
                @csrf
                @method('delete')
                <button class="btn_red"><i class="ti ti-trash"></i>Eliminar asignatura</button>
            </form>
        </div>
    @endif



</div>



{{-- <script src="{{asset('js/obtener-materias.js')}}"></script> --}}

@endsection
