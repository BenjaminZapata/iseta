@extends('Admin.template')

@section('content')
    <div class="edit-form-container">
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR MESA'])
            <div class="perfil__header">
                <h2>{{ $mesa->asignatura?->nombre }}</h2>
            </div>
            <div class="perfil__info">
                <div class="perfil_dataname">
                    <label>Carrera:</label>
                    <p class="px-2">{{ $mesa->asignatura->carrera->first()?->nombre }} -
                        {{ $mesa->asignatura->anioStr() }}
                    </p>
                </div>
                <form method="post" action="{{ route('admin.mesas.update', ['mesa' => $mesa->id]) }}">
                    @csrf
                    @method('put')

                    <div class="perfil_dataname">
                        <label>Fecha:</label>
                        <input type="datetime-local" class="campo_info rounded" value="{{ $mesa->fecha }}" name="fecha">
                    </div>
                    <div class="perfil_dataname">
                        <label>Llamado:</label>
                        <select class="campo_info rounded" name="llamado">
                            <option @selected($mesa->llamado == 1) value="1">Primero</option>
                            <option @selected($mesa->llamado == 2) value="2">Segundo</option>
                        </select>
                    </div>
                    <div class="perfil_dataname">
                        <label>Prof. presidente:</label>
                        <select class="campo_info rounded" name="prof_presidente">
                            <option value="0" @selected($mesa->prof_presidente == 0)>Vacio/A confirmar</option>
                            @foreach ($profesores as $profesor)
                                <option value="{{ $profesor->id }}" @selected($mesa->prof_presidente != 0 && $mesa->profesor?->id == $profesor->id)>
                                    {{ $profesor->apellidoNombre() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="perfil_dataname">
                        <label>Prof. vocal 1:</label>
                        <select class="campo_info rounded" name="prof_vocal_1">
                            <option value="0" @selected($mesa->prof_vocal_1 == 0)>Vacio/A confirmar</option>
                            @foreach ($profesores as $profesor)
                                <option value="{{ $profesor->id }}" @selected($mesa->prof_vocal_1 != 0 && $mesa->vocal1?->id == $profesor->id)>
                                    {{ $profesor->apellidoNombre() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="perfil_dataname">
                        <label>Prof. vocal 2:</label>
                        <select class="campo_info rounded" name="prof_vocal_2">
                            <option value="0" @selected($mesa->prof_vocal_2 == 0)>Vacio/A confirmar</option>
                            @foreach ($profesores as $profesor)
                                <option value="{{ $profesor->id }}" @selected($mesa->prof_vocal_2 != 0 && $mesa->vocal2?->id == $profesor->id)>
                                    {{ $profesor->apellidoNombre() }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <div class="botones-derecha">
                        <x-btn-cancelar />
                        <button type="submit" class="btn_blue">
                            <i class="ti ti-refresh" style="font-size: 1.3em; margin-right: 8px;"></i>
                            Actualizar
                        </button>
                    </div>
                </form>
                <div class="boton-eliminar">
                    @if (!$config['modo_seguro'])
                        <div>
                            <form method="POST" class="form-eliminar"
                                action="{{ route('admin.mesas.destroy', ['mesa' => $mesa->id]) }}">
                                @csrf
                                @method('delete')
                                <button class="btn_red_outline"
                                    onclick="openGeneralModal('form-eliminar-{{ $mesa->id }}', '¿Estás seguro de que querés eliminar la mesa: {{ strtoupper($mesa->asignatura->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                    class="btn_icon-danger" style=" margin-left: 10px;">
                                    <i class="ti ti-trash" style="font-size: 1.3em;"></i>Eliminar
                                    mesa
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>



        <div class="perfil_one br">
            {{-- <p>La funcion de agregar alumnos se elimino hasta que se arreglen algunos errores</p> --}}
            <div class="perfil__header">
                <h2>Alumnos inscriptos</h2>
            </div>
            <div class="matricular">
                @if (true || strtotime($mesa->fecha) > time())
                    <p class="py-2">Estos alumnos han aprobado la cursada de esta materia, luego se volvera a validar
                        sobre correlativas y tiempos</p>

                    <form method="POST" action="{{ route('admin.examenes.store') }}">
                        @csrf
                        <select class="rounded" name="id_alumno">
                            <option value="">Selecciona un alumno</option>
                            @foreach ($inscribibles as $inscribible)
                                <option value="{{ $inscribible->id }}">{{ $inscribible->apellidoNombre() }}</option>
                            @endforeach
                        </select>
                        <input name="id_mesa" value="{{ $mesa->id }}" type="hidden">

                        <div class="upd"><button class="btn_blue"><i class="ti ti-upload"
                                    style="font-size: 1.3em; margin-right: 8px;"></i>Cargar</button></div>

                    </form>
                @else
                    Ya no se pueden agregar alumnos
                @endif
            </div>

        </div>
        <div class="table" style="border-radius: 0.8rem">
            <div class="table__header" style="border-radius: 0.8rem 0.8rem 0 0 ;">
                <h2>Acta volante</h2>
                <div class="flex just-center">
                    <div class="dropdown" style="position: relative;">
                        <button class="btn_exportar" onclick="toggleExportar()" type="button">
                            <i class="ti ti-file-download" style="font-size: 1.3em; margin-right: 8px;"></i>Exportar acta
                            volante...
                        </button>
                        <div id="exportar-opciones"
                            style="display: none; position: absolute; right: 0; top: 100%; background: white; border: 1px solid #ccc; padding: 8px; z-index: 99;">
                            <a href="{{ route('admin.mesas.acta', ['mesa' => $mesa->id]) }}" target="_blank">
                                <button class="btn_sky" type="button" style="margin-right: 8px; width: 120px">
                                    <i class="ti ti-file-download" style="font-size: 1.3em; margin-right: 8px; "></i>Regular
                                </button>
                            </a>
                            <a href="{{ route('admin.mesas.actaprom', ['mesa' => $mesa->id]) }}" target="_blank">
                                <button class="btn_sky" type="button" style="margin-right: 8px; width: 120px">
                                    <i class="ti ti-file-download"
                                        style="font-size: 1.3em; margin-right: 8px; "></i>Promoción
                                </button>
                            </a>
                            <a href="{{ route('admin.mesas.actalibre', ['mesa' => $mesa->id]) }}" target="_blank">
                                <button class="btn_sky" style="margin-right: 8px; width: 120px">
                                    <i class="ti ti-file-download" style="font-size: 1.3em; margin-right: 8px;"></i>Libre
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table__body">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th class="center">Nota</th>
                        <th class="center">Cursada</th>
                        <th class="center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mesa->examenes as $examen)
                        <tr>
                            <td>{{ $examen->alumno->apellidoNombre() }}</td>
                            <td>
                                <div style="display:flex; align-items: center; justify-content: center;"
                                    title="0 = sin rendir, a = ausente">
                                    <form action="{{ route('admin.examenes.nota', ['examen' => $examen->id]) }}"
                                        method="POST">
                                        @csrf
                                        <div
                                            style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                            <input name="nota" placeholder="0 = sin rendir, a = ausente"
                                                class="input-nota" value="{{ $examen->nota() }}">
                                            <button class="boton-nota">
                                                <i class="ti ti-check" style="font-size: 1.3em;"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </td>

                            <td>
                                <div style="display:flex; align-items: center; justify-content: center;">
                                    @php
                                        $cursada = $mesa->asignatura->aproboCursada($examen->alumno);
                                    @endphp
                                    <a class="flex items-center justify-center"
                                        href="{{ route('admin.cursadas.edit', ['cursada' => $cursada->id, 'mesa' => $mesa->id, 'from' => 'mesas']) }}">
                                        <strong>ESTADO:</strong>
                                        <button class="btn_blue" style="text-transform: uppercase; margin-left: 8px;">
                                            {{ $cursada->condicionString() }}
                                            <i class="ti ti-edit" style="font-size: 1.3em; margin-left: 5px;"></i>
                                        </button>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; align-items: center; justify-content: center;">
                                    <a href="{{ route('admin.examenes.edit', ['examen' => $examen->id]) }}">
                                        <button class="btn_blue">
                                            <i class="ti ti-edit" style="font-size: 1.3em; margin-right: 8px;"></i>Editar
                                        </button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

<script>
    function toggleExportar() {
        const opciones = document.getElementById('exportar-opciones');
        opciones.style.display = opciones.style.display === 'none' ? 'block' : 'none';
    }

    // Opcional: cerrar si clickean fuera
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('exportar-opciones');
        const button = event.target.closest('.dropdown');

        if (!button) {
            dropdown.style.display = 'none';
        }
    });
</script>
