@extends('preceptor.template')

@section('content')
    <div class="perfil_one br">
        @include('preceptor.header-avatar', ['tituloSeccion' => 'CREAR MESA'])
        <div class="contenedor_mesa">

            <form method="post"
                action="{{ route('preceptor.mesas.dualpost', ['carrera' => $carrera->id, 'asignatura' => $asignatura->id]) }}">
                @csrf

                <div class="perfil_dataname">
                    <label>Materia: {{ $asignatura->nombre }}</label>
                </div>
                <div class="perfil_dataname">
                    <label>Profesor:</label>
                    <select class="profesor campo_info rounded" name="prof_presidente">
                        <option selected value="0">Vacio/A confirmar</option>
                        @foreach ($profesores as $profesor)
                            <option value="{{ $profesor->id }}">
                                {{ $profesor->apellido . ' ' . $profesor->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="perfil_dataname">
                    <label>Profesor 1:</label>
                    <select class="profesor campo_info rounded" name="prof_vocal_1">
                        <option selected value="0">Vacio/A confirmar</option>
                        @foreach ($profesores as $profesor)
                            <option value="{{ $profesor->id }}">
                                {{ $profesor->apellido . ' ' . $profesor->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="perfil_dataname">
                    <label>Profesor 2:</label>
                    <select class="profesor campo_info rounded" name="prof_vocal_2">
                        <option selected value="0">Vacio/A confirmar</option>
                        @foreach ($profesores as $profesor)
                            <option value="{{ $profesor->id }}">
                                {{ $profesor->apellido . ' ' . $profesor->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="perfil_dataname">
                    <label>Selecciona la cantidad de llamados:</label>
                    <select id="cantidad_llamados" name="cantidad_llamados" class="campo_info rounded">
                        <option value="1" selected>1 llamado</option>
                        <option value="2">2 llamados</option>
                    </select>
                </div>

                <div class="perfil_dataname" id="fecha_llamado_1">
                    <label>Fecha llamado 1:</label>
                    <input class="campo_info rounded" value="{{ old('fecha1') ? old('fecha1') : '' }}" type="datetime-local"
                        name="fecha1">
                </div>

                <div class="perfil_dataname" id="fecha_llamado_2" style="display: none;">
                    <label>Fecha llamado 2:</label>
                    <input class="campo_info rounded" value="{{ old('fecha2') ? old('fecha2') : '' }}" type="datetime-local"
                        name="fecha2">
                </div>

                <div class="botones-derecha"
                    style="margin-right: 27px; padding-top: 10px; padding-bottom: 16px; display: flex; gap: 12px; justify-content: flex-end;">
                    <x-btn-cancelar />
                    <button type="submit" class="btn_blue">
                        <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                        Crear
                    </button>
                </div>
            </form>

            <div class="my-5">
                <h2>Mesas de esta materia</h2>
                @foreach ($asignatura->mesas as $mesa)
                    <li>
                        <a href="{{ route('preceptor.mesas.edit', ['mesa' => $mesa->id]) }}">
                            <span class="blue-700">Llamado {{ $mesa->llamado }} <span>&#8599;</span>
                        </a>
                        </span> {{ $formatoFecha->dmhm($mesa->fecha) }}
                    </li>
                @endforeach
            </div>


            @if ($anterior)
                <div class="boton-anterior" title="{{ $anterior->nombre }}">
                    <a href="{{ route('preceptor.mesas.dual', ['carrera' => $carrera->id, 'asignatura' => $anterior->id]) }}"
                        style="display: flex; align-items: center;">
                        <i class="ti ti-chevron-left" style="font-size: 1.3em; font-weight: bold;"></i>
                        <span>Materia anterior</span>
                    </a>

                </div>
            @endif

            @if ($siguiente)
                <div class="boton-siguiente" title="{{ $siguiente->nombre }}">

                    <a href="{{ route('preceptor.mesas.dual', ['carrera' => $carrera->id, 'asignatura' => $siguiente->id]) }}"
                        style="display: flex; align-items: center;">
                        <span>Siguiente materia</span>
                        <i class="ti ti-chevron-right" style="font-size: 1.3em; font-weight: bold;"></i>
                    </a>
                </div>
            @endif


        </div>
    </div>

    <script src="{{ asset('js/obtener-materias.js') }}"></script>
    <script src="{{ asset('js/llamados.js') }}"></script>
@endsection
