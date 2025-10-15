@extends('preceptor.template')

@section('content')
    @php
        $ultimaCarreraSeleccionada = null;
        $ultimaAsignaturaSeleccionada = null;

    @endphp

    <div>
        <div class="perfil_one br">
            @include('preceptor.header-avatar', ['tituloSeccion' => 'CREAR NUEVA CURSADA'])
            <div class="perfil__info">


                <form method="post" action="{{ route('preceptor.cursadas.store') }}">
                    @csrf
                    <div class="perfil_dataname">
                        <label>Carrera:</label>
                        <select class="campo_info rounded" name="carrera" id="carrera_select">
                            <option disabled selected>Selecciona una carrera</option>
                            @foreach ($carreras as $carrera)
                                @php
                                    if (old('carrera') == $carrera->id) {
                                        $ultimaCarreraSeleccionada = $carrera;
                                    }
                                @endphp
                                <option @selected(old('carrera') == $carrera->id) value="{{ $carrera->id }}">{{ $carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Materia:</label>
                        <select id="asignatura_select" class="asignatura campo_info rounded" name="asignatura" required>
                            <option disabled selected>Selecciona una materia</option>
                            @if ($ultimaCarreraSeleccionada)
                                <option value="{{ old('asignatura') }}">
                                    {{ $ultimaCarreraSeleccionada->asignaturas->first()->nombre }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Alumno:</label>
                        <select class="alumno campo_info rounded" name="alumno">
                            <option disabled selected>Selecciona un alumno</option>
                            @foreach ($alumnos as $alumno)
                                <option @selected(old('id_alumno') == $alumno->id) value="{{ $alumno->id }}">
                                    {{ $alumno->apellidoNombre() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Año de cursada:</label>
                        <input class="campo_info rounded" type="number" name="anio_cursada" required>
                    </div>
                    <div x-data="{
                        condicion: '{{ old('condicion', '') }}',
                        aprobada: '{{ old('aprobada', '') }}'
                    }"
                    x-init="$watch('condicion', value => { if (value === '6') aprobada = null })">

                    {{-- Condición --}}
                    <div class="perfil_dataname">
                        <label>Condicion:</label>
                        <select class="campo_info rounded" name="condicion" x-model="condicion">
                            <option @selected(old('condicion') == 1) value="1">Regular</option>
                            <option @selected(old('condicion') == 0) value="0">Libre</option>
                            <option @selected(old('condicion') == 5) value="5">Itinerante</option>
                            <option @selected(old('condicion') == 6) value="6">Oyente</option>
                        </select>
                    </div>

                    {{-- Estado, solo aparece si no es Oyente --}}
                    <template x-if="condicion !== '6'">
                        <div x-transition>
                            <div class="perfil_dataname">
                                <label>Estado:</label>
                                <select class="campo_info rounded" name="aprobada" x-model="aprobada">
                                    <option value="1">Aprobada</option>
                                    <option value="2">Desaprobada</option>
                                    <option value="3">Cursando</option>
                                    <option value="4">Promocionada</option>
                                    <option value="5">Equivalencia</option>
                                </select>
                            </div>

                            <div class="perfil_dataname" x-show="aprobada === '5'" x-transition>
                                <label>Nota:</label>
                                <input class="campo_info rounded" name="nota" type="number" />
                            </div>
                        </div>
                    </template>

                    {{-- Hidden para mandar null si Oyente --}}
                    <template x-if="condicion === '6'">
                        <input type="hidden" name="aprobada" :value="null">
                    </template>
                </div>
                    <div class="botones-derecha">
                        <div class="botones-derecha"
                            style="margin-right: 27px; padding-top: 10px; padding-bottom: 16px; display: flex; gap: 12px; justify-content: flex-end;">
                            <x-botones-alumno />
                            <x-btn-cancelar />
                            <button type="submit" class="btn_blue">
                                <i class="ti ti-refresh" style="font-size: 1.3em; margin-right: 8px;"></i>
                                Crear
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/obtener-materias.js') }}"></script>
@endsection