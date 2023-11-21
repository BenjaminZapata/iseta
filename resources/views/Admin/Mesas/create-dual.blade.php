@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Crear mesas de primer y segundo llamado</h2>
            </div>
            <div class="perfil__info">

                <form method="post" action="{{route('admin.mesas.dual', ['asignatura'=>$asignatura->id])}}">
                @csrf

                <div class="perfil_dataname">
                    <label>Materia: {{$asignatura->nombre}}</label>
                </div>
                <div class="perfil_dataname">
                    <label>Profesor:</label>
                    <select class="profesor campo_info rounded" name="prof_presidente">
                        <option selected value="0">Vacio/A confirmar</option>
                        @foreach ($profesores as $profesor)
                        <option value="{{$profesor->id}}">
                            {{$profesor->apellido . ' ' . $profesor->nombre}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="perfil_dataname">
                    <label>Profesor 1:</label>
                    <select class="profesor campo_info rounded" name="prof_vocal_1">
                        <option selected value="0">Vacio/A confirmar</option>
                        @foreach ($profesores as $profesor)
                        <option value="{{$profesor->id}}">
                            {{$profesor->apellido . ' ' . $profesor->nombre}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="perfil_dataname">
                    <label>Profesor 2:</label>
                    <select class="profesor campo_info rounded" name="prof_vocal_2">
                        <option selected value="0">Vacio/A confirmar</option>
                        @foreach ($profesores as $profesor)
                        <option value="{{$profesor->id}}">
                            {{$profesor->apellido . ' ' . $profesor->nombre}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="py-2 perfil_dataname">
                    <label>Fecha de los llamados</label>
                    <p class="px-4 font-3 font-400">Deja uno vacio si solo quieres crear un llamado</p>
                </div>
                <div class="perfil_dataname">
                    <label>Fecha llamado 1:</label>
                    <input class="campo_info rounded" value="{{old('fecha1')?old('fecha'):''}}" type="datetime-local" name="fecha1">
                </div>

                <div class="perfil_dataname">
                    <label>Fecha llamado 2:</label>
                    <input class="campo_info rounded" value="{{old('fecha2')?old('fecha'):''}}" type="datetime-local" name="fecha2">
                </div>

                <div class="upd"><button class="btn_blue"><i class="ti ti-circle-plus"></i>Crear</button></div>
                </form>

                <div class="my-5">
                    <h2>Mesas de esta materia</h2>
                    @foreach ($asignatura->mesas as $mesa)
                        <li>
                            <a href="{{route('admin.mesas.edit',['mesa' => $mesa->id])}}">
                                <span class="blue-700">Llamado {{$mesa->llamado}} <span>&#8599;</span>
                            </a>
                            </span> {{$formatoFecha->dmhm($mesa->fecha)}} 
                        </li>                        
                    @endforeach
                </div>

                @if ($anterior)
                    <div><a href="{{route('admin.mesas.dual', ['asignatura'=>$anterior->id])}}">Anterior: {{$anterior->nombre}}</a></div>
                @endif
                @if ($siguiente)
                    <div><a href="{{route('admin.mesas.dual', ['asignatura'=>$siguiente->id])}}">Siguiente: {{$siguiente->nombre}}</a></div>
                @endif
            </div>
  
        </div>
    </div>

    <script src="{{asset('js/obtener-materias.js')}}"></script>

@endsection
