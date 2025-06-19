@extends('Admin.template')

@section('content')
    <p class="w-100p">
        <a href="/admin/alumnos">Alumnos</a>/
        <a href="/admin/alumnos/{{$cursada->alumno->id}}/edit">{{$cursada->alumno->id}}</a>/ Cursada/
        <a>{{$cursada->asignatura->nombre}}</a>
    </p>
        <div class="edit-form-container">
            <div class="perfil_one br">
                <div class="perfil__header">
                    <h2>Cursada</h2>
                </div>
                <div class="perfil__info">

                    <form method="post" action="{{route('admin.cursadas.update', ['cursada'=>$cursada->id])}}">
                    @csrf
                    @method('put')
                    <div class="perfil_dataname">
                        <label>Carrera:</label>
                        <span class="campo_info2">{{$cursada->asignatura->carrera->first()?->nombre}}</span>
                    </div>
                    <div class="perfil_dataname">
                        <label>Materia:</label>
                        <span class="campo_info2">{{$cursada->asignatura->nombre}}</span>
                    </div>
                    <div class="perfil_dataname">
                        <label>Alumno/a:</label>
                        <span class="campo_info2">{{$cursada->alumno->apellidoNombre()}}</span>
                    </div>
                    <div class="perfil_dataname">
                        <label>Año de cursada:</label>
                        <input class="campo_info rounded" value="{{$cursada->anio_cursada}}" name="anio_cursada">
                    </div>
                    <div class="perfil_dataname">
                        <label>Condicion:</label>
                        @php
                            $condiciones = [
                                0 => 'Libre',
                                1 => 'Regular',
                                2 => 'Promocion',
                                3 => 'Equivalencia',
                                4 => 'Desertor',
                                5 => 'Itinerante',
                                6 => 'Oyente',
                            ];

                            // Valores que NO deben mostrarse en el dropdown
                            $condicionesExcluidas = [0, 2, 3];

                            $condicionActual = $cursada->condicion;
                        @endphp

                        <select class="campo_info rounded" name="condicion">
                            {{-- Mostrar la condición actual si está entre las excluidas --}}
                            @if(in_array($condicionActual, $condicionesExcluidas))
                                <option value="{{ $condicionActual }}" selected hidden>{{ $condiciones[$condicionActual] }}</option>
                            @endif

                            {{-- Mostrar las condiciones que NO están en las excluidas --}}
                            @foreach($condiciones as $valor => $texto)
                                @if(!in_array($valor, $condicionesExcluidas))
                                    <option value="{{ $valor }}" @selected($condicionActual == $valor)>{{ $texto }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Aprobada:</label>
                        <select class="campo_info rounded" name="aprobada">
                            <option @selected($cursada->aprobada==1) value="1">Si</option>
                            <option @selected($cursada->aprobada==2) value="2">No</option>
                            <option @selected($cursada->aprobada==3) value="3">Vacio/cursando</option>
                            <option @selected($cursada->aprobada==4) value="4">Promocion</option>
                            <option @selected($cursada->aprobada==5) value="5">Equivalencia</option>
                        </select>
                    </div>

                    <input type="hidden" value="{{url()->previous()}}" name="redirect">

                    <div class="upd"><button class="btn_blue"><i class="ti ti-refresh"></i>Actualizar</button></div>
                    </form>
                </div>
            </div>
@if (!$config['modo_seguro'])
            <div class="upd">
                <form class="form-eliminar" method="post" action="{{route('admin.cursadas.destroy', ['cursada'=>$cursada->id])}}">
                    @csrf
                    @method('delete')
                    <button class="btn_red"><i class="ti ti-trash"></i>Eliminar cursada</button>
                </form>
            </div>
            @endif
        </div>

@endsection
