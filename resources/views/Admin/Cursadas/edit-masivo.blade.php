@extends('Admin.template')

@section('content')
    <div>
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Cargar resultados de cursadas</h2>
                <form method="POST" action="{{route('admin.config.setone')}}">
                    <div class="flex items-center bot-masivo">
                        @csrf
                        Año
                        <input class="black rounded" name="anio_ciclo_actual" value="{{$config['anio_ciclo_actual']}}" type="number">
                        <button class="btn_grey">Cambiar</button>
                    </div>
                </form>
            </div>

            <div class="perfil__info">

                <div>
                    <h2>{{$asignatura->carrera->nombre}}</h2>
                    <span>{{$asignatura->anioStr()}}</span>
                </div>

                <div class="grid-3 gridx-center gap-4 selec-masivo">
                    <div>
                        <p>Anterior</p>
                        @if ($anterior)
                            <a class="blue-600" href="{{route('admin.cursadas.masivo', ['asignatura'=>$anterior->id])}}">{{$anterior->nombre}}</a>
                        @endif
                    </div>
                    <div>
                        <p>Actual</p>
                        {{$asignatura->nombre}}
                    </div>
                    <div>
                        <p>Siguiente</p>
                        @if ($siguiente)
                            <a class="blue-600" href="{{route('admin.cursadas.masivo', ['asignatura'=>$siguiente->id])}}">{{$siguiente->nombre}}</a>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{route('admin.cursadas.masivo.post')}}">
                    @csrf
                    @foreach($asignatura->cursadas as $cursada)
                      <div class="w-100p grid-3 perfil_dataname-rem">
                        <span class="remat w-100p">{{$cursada->alumno->apellidoNombre()}}</span>
                        <div>
                            <select class="campo_info-rem p-2 border-1 rounded" name="{{$cursada->id}}">
                                <option @selected($cursada->condicion==1 && $cursada->aprobada==3) value="rc">Regular cursando</option>
                                <option @selected($cursada->condicion==1 && $cursada->aprobada==2) value="rd">Regular desaprobado</option>
                                <option @selected($cursada->condicion==1 && $cursada->aprobada==1) value="ra">Regular aprobado</option>
                                <option @selected($cursada->condicion==0) value="l">Libre</option>
                                <option @selected($cursada->condicion==3) value="e">Equivalencia</option>
                                <option @selected($cursada->condicion==2) value="p">Promoción</option>
                            </select>
                        </div>
                      </div>
                    @endforeach
                    <div class="upd"><button class="btn_blue"><i class="ti ti-upload"></i>Cargar</button></div>

                </form>

                  <br>

                  <div class="flex-col">
                    <h3 class="h-sub">Seleccionar carrera</h3>
                    @foreach ($carreras as $carrera)
                        @if ($carrera->primeraAsignatura())
                            @if ($carrera->id == $asignatura->carrera->id)
                                <p class="text-left gray-700 perfil_dataname-rem" href="{{route('admin.cursadas.masivo',['asignatura'=>$carrera->primeraAsignatura()->id])}}">
                                    {{$carrera->nombre}}
                                </p>
                            @else
                                <a class="text-left blue-700 perfil_dataname-rem" href="{{route('admin.cursadas.masivo',['asignatura'=>$carrera->primeraAsignatura()->id])}}">
                                    {{$carrera->nombre}}
                                </a>
                            @endif
                        @endif
                    @endforeach
                  </div>


            </div>

        </div>
    </div>

    <script src="{{asset('js/obtener-materias.js')}}"></script>

@endsection
