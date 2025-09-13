@extends('Admin.template')

@section('content')
    @php
        $carrera_previa = null;
    @endphp
    @php
        $mostrar_botones = false; // Controla si mostrar o no los botones
    @endphp

    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'CREAR MESA'])

            <div class="perfil__info">
                <form method="post" action="{{ route('admin.mesas.store') }}">

                    <div class="perfil_dataname">
                        <label>Carrera:</label>
                        <select class="campo_info rounded" name="carrera" id="carrera_select">
                            <option value="any">Selecciona una carrera</option>

                            @foreach ($carreras as $carrera)
                                @php
                                    $selected =
                                        $precargados['carrera'] == $carrera->id || old('carrera') == $carrera->id;
                                    if ($selected) {
                                        $carrera_previa = $carrera;
                                    }
                                @endphp

                                <option @selected($selected) value="{{ $carrera->id }}">
                                    {{ $carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @csrf

                    <div class="perfil_dataname">

                        @php
                            $asig = null;
                            if ($carrera_previa) {
                                $asig = $carrera_previa->asignaturas->where('id', old('id_asignatura'))->first();
                            }
                        @endphp


                        <label>Materia:</label>
                        <select class="campo_info rounded" id="asignatura_select" name="id_asignatura">

                            @if ($precargados['asignatura'])
                                <option selected value="{{ $precargados['asignatura']->id }}">
                                    {{ $precargados['asignatura']->nombre }}</option>
                            @elseif($asig)
                                <option selected value="{{ $asig->id }}">{{ $asig->nombre }}</option>
                            @endif
                            <option value="">Selecciona una carrera</option>
                        </select>
                    </div>

                    <div class="perfil_dataname">
                        <label>Profesor:</label>
                        <select class="profesor campo_info rounded" name="prof_presidente">
                            <option selected value="0">Vacio/A confirmar</option>
                            @foreach ($profesores as $profesor)
                                <option @selected(old('prof_presidente') == $profesor->id) value="{{ $profesor->id }}">
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
                                <option @selected(old('prof_vocal_1') == $profesor->id) value="{{ $profesor->id }}">
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
                                <option @selected(old('prof_vocal_2') == $profesor->id) value="{{ $profesor->id }}">
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
                        <input class="campo_info rounded" value="{{ old('fecha1') ? old('fecha1') : '' }}"
                            type="datetime-local" name="fecha1">
                    </div>

                    <div class="perfil_dataname" id="fecha_llamado_2" style="display: none;">
                        <label>Fecha llamado 2:</label>
                        <input class="campo_info rounded" value="{{ old('fecha2') ? old('fecha2') : '' }}"
                            type="datetime-local" name="fecha2">
                    </div>


                </form>
                 <div class="botones-derecha"
                            style="margin-right: 27px; padding-top: 10px; padding-bottom: 16px; display: flex; gap: 12px; justify-content: flex-end;">
                            
                            <x-btn-cancelar />
                <button type="submit" class="btn_blue">
                    <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                       Crear
                 </button>
            </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/obtener-materias.js') }}"></script>
    <script src="{{ asset('js/llamados.js') }}"></script>
@endsection
