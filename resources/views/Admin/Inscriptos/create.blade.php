@extends('Admin.template')

@section('content')

    @php
        $alumno_id = request()->get('alumno_id');
        $alumno_preseleccionado = null;

        if ($alumno_id) {
            $alumno_preseleccionado = $alumnos->firstWhere('id', $alumno_id);
        }
    @endphp

    <div>
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'INSCRIBIR ALUMNO']) 

            <div class="perfil__info">
                <form method="post" action="{{ route('admin.inscriptos.store') }}">
                    @csrf

                    {{-- Alumno --}}
                    <div class="perfil_dataname">
                        <label>Alumno:</label>

                        @if ($alumno_preseleccionado)
                            <div class="campo_info2">{{ $alumno_preseleccionado->apellidoNombre() }}</div>
                            <input type="hidden" name="id_alumno" value="{{ $alumno_preseleccionado->id }}">
                        @else
                            <select class="campo_info rounded" name="id_alumno" required>
                                <option value="">Selecciona un alumno</option>
                                @foreach ($alumnos as $alumno)
                                    <option value="{{ $alumno->id }}">{{ $alumno->apellidoNombre() }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    {{-- Carrera --}}
                    <div class="perfil_dataname">
                        <label>Carrera:</label>
                        <select class="campo_info rounded" name="id_carrera" required>
                            @foreach ($carreras as $carrera)
                                <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Año inscripción --}}
                    <div class="perfil_dataname">
                        <label>Año inscripción:</label>
                        <input class="campo_info rounded" name="anio_inscripcion"
                            value="{{ old('anio_inscripcion', date('Y')) }}">
                    </div>

                    {{-- Índice libro matriz --}}
                    <div class="perfil_dataname">
                        <label>Índice libro matriz:</label>
                        <input class="campo_info rounded" name="indice_libro_matriz"
                            value="{{ old('indice_libro_matriz') }}">
                    </div>

                    {{-- Año finalización --}}
                    <div class="perfil_dataname">
                        <label>Año finalización:</label>
                        <input class="campo_info rounded" name="anio_finalizacion" value="{{ old('anio_finalizacion') }}">
                    </div>

                    {{-- Estado --}}
                    <div class="perfil_dataname">
                        <label>Estado:</label>
                        <input class="campo_info rounded" type="text" name="estado_texto" value="Cursando" readonly>
                        <input type="hidden" name="estado" value="0">
                    </div>

                    {{-- Redirección --}}
                    <input type="hidden" name="redirect" value="{{ url()->previous() }}">

                    <div class="upd">
                        <button class="btn_blue"><i class="ti ti-circle-plus"></i> Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection