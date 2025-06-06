@extends('Admin.template')

@section('content')
    <div class="edit-form-container">
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>{{ $mesa->asignatura?->nombre }}</h2>


            </div>
            <div class="perfil__info">
                <div class="perfil_dataname">
                    <label>Carrera:</label>
                    <p class="px-2">{{$mesa->asignatura->carrera->first()?->nombre}} - {{$mesa->asignatura->anioStr()}}</p>
                </div>
                <form method="post" action="{{route('admin.mesas.update', ['mesa'=>$mesa->id])}}">
                @csrf
                @method('put')

                    <div class="perfil_dataname">
                        <label>Fecha:</label>
                        <input type="datetime-local" class="campo_info rounded" value="{{$mesa->fecha}}" name="fecha">
                    </div>
                    <div class="perfil_dataname">
                        <label>Llamado:</label>
                        <select class="campo_info rounded" name="llamado">
                            <option @selected($mesa->llamado==1) value="1">Primero</option>
                            <option @selected($mesa->llamado==2) value="2">Segundo</option>
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Prof. presidente:</label>
                        <select class="campo_info rounded" name="prof_presidente">
                            <option @selected($mesa->prof_presidente==0) value="0">Vacio/A confirmar</option>
                            @foreach($profesores as $profesor)
                            <option @selected($mesa->prof_presidente != 0 && $mesa->profesor->id == $profesor->id) value="{{$profesor->id}}">
                                {{$profesor->apellidoNombre()}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Prof. vocal 1:</label>
                        <select class="campo_info rounded" name="prof_vocal_1">
                            <option @selected($mesa->prof_vocal_1==0) value="0">Vacio/A confirmar</option>

                        @foreach($profesores as $profesor)
                            <option @selected($mesa->prof_vocal_1 != 0 && $mesa->vocal1->id == $profesor->id) value="{{$profesor->id}}">
                                {{$profesor->apellidoNombre()}}
                            </option>
                        @endforeach
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Prof. vocal 2:</label>
                        <select class="campo_info rounded" name="prof_vocal_2">
                            <option @selected($mesa->prof_vocal_2==0) value="0">Vacio/A confirmar</option>
                        @foreach($profesores as $profesor)
                            <option @selected($mesa->prof_vocal_2 != 0 && $mesa->vocal2 && $mesa->vocal2->id == $profesor->id) value="{{$profesor->id}}">
                                {{$profesor->apellidoNombre()}}

                            </option>
                        @endforeach
                        </select>
                    </div>

                    <div class="upd">
                        <button class="btn_blue"><i class="ti ti-refresh"></i>Actualizar</button>
                    </div>
                </form>
            </div>
        </div>



            <div class="perfil_one br">
                {{-- <p>La funcion de agregar alumnos se elimino hasta que se arreglen algunos errores</p> --}}
                <div class="perfil__header">
                    <h2>Alumnos inscriptos</h2>
                </div>
                <div class="matricular">
                     @if(true || strtotime($mesa->fecha) > time())
                        <p class="py-2">Estos alumnos han aprobado la cursada de esta materia, luego se volvera a validar sobre correlativas y tiempos</p>

                        <form method="POST" action="{{route('admin.examenes.store')}}">



                    @csrf
                            <select class="rounded" name="id_alumno">
                                    <option value="">Selecciona un alumno</option>
                                @foreach ($inscribibles as $inscribible)
                                    <option value="{{$inscribible->id}}">{{$inscribible->apellidoNombre()}}</option>
                                @endforeach
                            </select>
                            <input name="id_mesa" value="{{$mesa->id}}" type="hidden">

                            <div class="upd"><button class="btn_blue"><i class="ti ti-upload"></i>Cargar</button></div>

                        </form>

                    @else
                        Ya no se pueden agregar alumnos
                    @endif
                </div>

            </div>
            <div class="table">
                <div class="table__header">
                    <h2>Acta volante</h2>
                    <div class="flex just-center">
                        <a href="{{route('admin.mesas.acta', ['mesa'=>$mesa->id])}}" target="_blank"><button class="btn_grey">Regular</button></a>
                        <a href="{{route('admin.mesas.actaprom', ['mesa'=>$mesa->id])}}" target="_blank"><button class="btn_grey">Promoción</button></a>
                        <a href="{{route('admin.mesas.actalibre', ['mesa'=>$mesa->id])}}" target="_blank"><button class="btn_grey">Libre</button></a>

                    </div>
                </div>

                <table class="table__body">
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Nota</th>
                            <th>Cursada</th>
                            <th class="center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mesa->examenes as $examen)
                        <tr>
                            <td>{{$examen->alumno->apellidoNombre()}}</td>
                            <td>
                                {{-- @dd($examen->id) --}}
                                <form action="{{route('admin.examenes.nota', ['examen'=>$examen->id])}}" method="POST">
                                    @csrf
                                    <input name="nota" placeholder="0 = sin rendir, a = ausente" class="p-1 rounded border-1" value="{{$examen->nota()}}">
                                    <button class="p-1 rounded border-1">modificar</button>
                                </form>
                            </td>
                            <td>
                                @php
                                    $cursada = $mesa->asignatura->aproboCursada($examen->alumno)
                                @endphp
                                <a class="flex items-center" href="{{route('admin.cursadas.edit',['cursada'=>$cursada->id])}}">
                                    {{$cursada->condicionString()}}   <i class="ti ti-info-circle"></i>
                                </a>
                            </td>
                            <td class=" flex just-center">
                                <a href="{{route('admin.examenes.edit', ['examen' => $examen->id])}}">
                                    <button class="btn_blue"><i class="ti ti-edit"></i>Editar</button>
                                </a>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
@if (!$config['modo_seguro'])
        <div class="upd">
            <form method="POST" class="form-eliminar" action="{{route('admin.mesas.destroy', ['mesa' => $mesa->id])}}">
                @csrf
                @method('delete')
                <button class="btn_red"><i class="ti ti-trash"></i>Eliminar mesa</button>
            </form>
        </div>
        @endif
    </div>
@endsection
