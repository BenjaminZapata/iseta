@extends('Admin.template')

@section('content')
<div class="edit-form-container">
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR CARRERA'])
        <div class="perfil__info">
            {{-- BOTÓN SWITCH DE ESTADO
            
            <div
                style="margin: 10px 5px; display: flex; align-items: center; gap: 12px; justify-content: flex-end; padding-right: 25px;">
                <span style="font-weight: 600;">
                    Estado de la carrera:
                    <span id="estado-texto" style="color: {{ $carrera->vigente ? '#16a34a' : '#dc2626' }};">
            {{ $carrera->vigente ? 'Activa' : 'Inactiva' }}
            </span>
            </span>

            <label class="switch" title="{{ $carrera->vigente ? 'Desactivar carrera' : 'Activar carrera' }}">
                <input type="checkbox" id="toggle-carrera-{{ $carrera->id }}"
                    @if ($carrera->vigente) checked @endif
                onchange="onToggleCarrera(this, {{ $carrera->id }})">
                <span class="slider"></span>
            </label>
        </div>

        --}}

        <form method="POST" action="{{ route('admin.carreras.update', ['carrera' => $carrera->id]) }}">
            @method('PUT')
            @csrf
            <fieldset class="p-2" style="margin:10px;">
                <legend class="font-600 font-7">Información</legend>
                <div class="grid-2 gap-2 p-0">
                    <label class="perfil_dataname">Nombre:
                        <p class="campo_info-noinput rounded"> {{ $carrera->nombre }} </p>
                    </label>
                    <label class="perfil_dataname">Resolucion:
                        <p class="campo_info-noinput rounded"> {{ $carrera->resolucion }} </p>
                    </label>
                    <label class="perfil_dataname">Año de apertura:
                        <p class="campo_info-noinput rounded"> {{ $carrera->anio_apertura }} </p>
                        <input type="hidden" name="anio_apertura" value="{{ $carrera->anio_apertura }}">
                    </label>
                    <label class="label-input-y-75">Estado:
                        <select name="vigente" class="campo_info rounded" value="{{ $carrera->vigente }}">
                            <option value="{{ $carrera->vigente }}" selected>
                                {{ $carrera->vigente ? 'Vigente' : 'No vigente' }}
                            </option>
                            @if ($carrera->vigente == 1)
                            <option value="0">No vigente</option>
                            @else
                            <option value="1">Vigente</option>
                            @endif
                        </select>
                    </label>
                    <label class="label-input-y-75">Año de cierre:
                        <input type="text" name="anio_fin" value="{{ $carrera->anio_fin }}">
                    </label>
                    <label class="label-input-y-75">Observaciones:
                        <textarea name="observaciones" cols="20" rows="3">{{ $carrera->observaciones }}</textarea>
                    </label>
                    <input type="hidden" name="texthidden" value="{{ url()->previous() }}">
                </div>
            </fieldset>
            <div class="botones-derecha">

                <x-botones-alumno />
                {{-- @if (isset($mostrar_botones) && $mostrar_botones) --}}
                <x-btn-cancelar />
                <button type="submit" class="btn_blue">
                    @if ($method == 'put')
                    <i class="ti ti-refresh" style="font-size: 1.3em; margin-right: 8px;"></i>
                    Actualizar
                    @elseif ($method == 'post')
                    <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                    Guardar
                    @endif
                    {{-- @endif --}}
                </button>

            </div>
        </form>
        <div class="boton-eliminar">
            @if (!$config['modo_seguro'])
            <div>
                <form method="POST" id="form-eliminar-{{ $carrera->id }}"
                    action="{{ route('admin.carreras.destroy', ['carrera' => $carrera->id]) }}"
                    style="margin-left: 10px;">
                    @csrf
                    @method('delete')
                    <button type="button" class="btn_red_outline"
                        onclick="openGeneralModal(
                                            'form-eliminar-{{ $carrera->id }}', 
                                            '¿Estás seguro de que querés eliminar la carrera: {{ mb_strtoupper($carrera->nombre, 'UTF-8') }}?\n\nESTA ACCIÓN NO SE PUEDE DESHACER.'
                                        )">
                        <i class="ti ti-trash" style="font-size: 1.3em;"></i> Eliminar carrera
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>


{{-- BOTONES SUPERIORES --}}
<div class="table">
    <div class="perfil__header-alt">
        <a href="{{ route('admin.carreras.addAsignaturaView', ['carrera' => $carrera->id]) }}">
            <button class="btn_blue"><i class="ti ti-circle-plus"
                    style="font-size: 1.3em; margin-right: 8px;"></i>Agregar
                asignatura</button>
        </a>

        {{-- BOTÓN GENERAL DE EXPORTACIÓN --}}
        <div style="position: relative;">
            <button type="button" class="btn_exportar" onclick="toggleFiltroExportar(this)">
                <i class="ti ti-file-download"></i> Exportar cursadas
            </button>
            <form method="GET" action="{{ route('excel.cursadas.carrera', ['carrera' => $carrera->id]) }}"
                class="filtro-exportar">
                <div style="display: flex; flex-direction: column; align-items: flex-start;">
                    <select name="genero">
                        <option value="">-- Género --</option>
                        <option value="f" {{ request('genero') == 'f' ? 'selected' : '' }}>Femenino</option>
                        <option value="m" {{ request('genero') == 'm' ? 'selected' : '' }}>Masculino</option>
                        <option value="o" {{ request('genero') == 'o' ? 'selected' : '' }}>Otro</option>
                    </select>

                    <select name="anio">
                        <option value="">-- Año calendario --</option>
                        @php
                        $aniosCalendario = $aniosPorCarrera[$carrera->id] ?? [];
                        @endphp
                        @foreach ($aniosCalendario as $anio)
                        <option value="{{ $anio }}" {{ request('anio') == $anio ? 'selected' : '' }}>
                            {{ $anio }}
                        </option>
                        @endforeach
                    </select>

                    <select name="condicion">
                        <option value="">-- Condición --</option>
                        <option value="regular" {{ request('condicion') == 'regular' ? 'selected' : '' }}>Regular
                        </option>
                        <option value="libre" {{ request('condicion') == 'libre' ? 'selected' : '' }}>Libre
                        </option>
                        <option value="promocion" {{ request('condicion') == 'promocion' ? 'selected' : '' }}>
                            Promoción</option>
                        <option value="equivalencia"
                            {{ request('condicion') == 'equivalencia' ? 'selected' : '' }}>Equivalencia</option>
                        <option value="desertor" {{ request('condicion') == 'desertor' ? 'selected' : '' }}>
                            Desertor</option>
                        <option value="itinerante" {{ request('condicion') == 'itinerante' ? 'selected' : '' }}>
                            Itinerante</option>
                        <option value="oyente" {{ request('condicion') == 'oyente' ? 'selected' : '' }}>Oyente
                        </option>
                    </select>

                    <button class="btn_blue">
                        <i class="ti ti-file-export" style="font-size: 1.3em; margin-right: 8px;"></i> Descargar
                    </button>
                </div>
            </form>
        </div>

        {{-- Formularios ocultos --}}
        <form id="form-desactivar-{{ $carrera->id }}" action="{{ route('admin.carreras.desactivar', $carrera) }}"
            method="POST" style="display:none;">
            @csrf
        </form>
        <form id="form-reactivar-{{ $carrera->id }}" action="{{ route('admin.carreras.reactivar', $carrera) }}"
            method="POST" style="display:none;">
            @csrf
        </form>
        </td>
    </div>

    {{-- A C O R D E O N  D E  A S I G N A T U R A S --}}
    <div class="accordion" id="asignaturasAccordion">
        @php
        $anio_actual = '';
        $anio_index = 0;
        @endphp

        @foreach ($carrera->asignaturas as $asignatura)
        @if ($anio_actual != $asignatura->anio)
        @if ($anio_actual != '')
        </tbody>
        </table>
    </div>
</div>
</div>
@endif

@php
$anio_index++;
$anio_actual = $asignatura->anio;
@endphp

<div class="accordion-item">
    <h2 class="accordion-header" id="headingAnio{{ $anio_index }}">
        <button class="accordion-button collapsed font-500" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseAnio{{ $anio_index }}" aria-expanded="false"
            aria-controls="collapseAnio{{ $anio_index }}">
            {{ $anio_actual }}° año
        </button>
    </h2>
    <div id="collapseAnio{{ $anio_index }}" class="accordion-collapse collapse"
        aria-labelledby="headingAnio{{ $anio_index }}" data-bs-parent="#asignaturasAccordion">
        <div class="accordion-body p-0">
            <table>
                <thead>
                    <tr>
                        <th class="center">Año</th>
                        <th>Materia</th>
                        <th class="center">Carga anual/semanal</th>
                        <th class="center">Acción</th>
                        <th class="center">Crear</th>
                        <th class="center">Exportar</th>
                    </tr>
                </thead>
                <tbody>
                    @endif

                    <tr>
                        <td class="center">{{ $asignatura->anio }}</td>
                        <td>{{ $asignatura->nombre }}</td>
                        <td class="center">{{ $asignatura->carga_horaria }} horas</td>
                        <td style="display:flex; align-items: center; justify-content: center;">
                            <form id="form-eliminar-{{ $asignatura->id }}"
                                action="{{ route('admin.asignaturas.destroy', $asignatura->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="openGeneralModal(
        'form-eliminar-{{ $asignatura->id }}',
        `¿Estás seguro de que querés eliminar la asignatura?\n\n
        Nombre: {{ strtoupper($asignatura->nombre) }}\n
        {{ isset($asignatura->cantidad_modulo) && $asignatura->cantidad_modulo ? 'Módulos: ' . $asignatura->cantidad_modulo : 'Carga horaria: ' . $asignatura->carga_horaria }}\n
         Año: {{ $asignatura->anio }}\n\n
         ESTA ACCIÓN NO SE PUEDE DESHACER.`)"
                                    class="btn_icon-danger" style="background-color: red; margin-left: 10px;">
                                    <i class="ti ti-trash" style="font-size: 1.3em;"></i>
                                </button>
                            </form>

                        </td>

                        <td>
                            <div style="display:flex; align-items: center; justify-content: center;">
                                <a
                                    href="{{ route('admin.mesas.dual', ['carrera' => $carrera->id, 'asignatura' => $asignatura->id]) }}">
                                    <button class="btn_blue">
                                        <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                                        Crear Mesa
                                    </button>
                                </a>
                            </div>
                        </td>
                        <td>
                            <div style="position: relative;">
                                <button type="button" class="btn_exportar" onclick="toggleFiltroExportar(this)">
                                    <i class="ti ti-file-download"></i> Exportar materia
                                </button>

                                <form method="GET"
                                    action="{{ route('excel.cursadas.carrera', ['carrera' => $carrera->id]) }}"
                                    class="filtro-exportar"
                                    style="display: none; position: absolute; top: 100%; left: 0; background: #fff; border: 1px solid #ccc; padding: 10px; z-index: 10; width: max-content; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">

                                    <input type="hidden" name="asignatura_id" value="{{ $asignatura->id }}">

                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        <select name="genero">
                                            <option value="">-- Género --</option>
                                            <option value="f" {{ request('genero') == 'f' ? 'selected' : '' }}>
                                                Femenino</option>
                                            <option value="m" {{ request('genero') == 'm' ? 'selected' : '' }}>
                                                Masculino</option>
                                            <option value="o" {{ request('genero') == 'o' ? 'selected' : '' }}>
                                                Otro</option>
                                        </select>

                                        <select name="anio">
                                            <option value="">-- Año calendario --</option>
                                            @php
                                            $aniosCalendario = $aniosPorCarrera[$carrera->id] ?? [];
                                            @endphp
                                            @foreach ($aniosCalendario as $anio)
                                            <option value="{{ $anio }}"
                                                {{ request('anio') == $anio ? 'selected' : '' }}>
                                                {{ $anio }}
                                            </option>
                                            @endforeach
                                        </select>

                                        <select name="condicion">
                                            <option value="">-- Condición --</option>
                                            <option value="regular"
                                                {{ request('condicion') == 'regular' ? 'selected' : '' }}>Regular
                                            </option>
                                            <option value="libre"
                                                {{ request('condicion') == 'libre' ? 'selected' : '' }}>Libre</option>
                                            <option value="promocion"
                                                {{ request('condicion') == 'promocion' ? 'selected' : '' }}>Promoción
                                            </option>
                                            <option value="equivalencia"
                                                {{ request('condicion') == 'equivalencia' ? 'selected' : '' }}>
                                                Equivalencia</option>
                                            <option value="desertor"
                                                {{ request('condicion') == 'desertor' ? 'selected' : '' }}>Desertor
                                            </option>
                                            <option value="itinerante"
                                                {{ request('condicion') == 'itinerante' ? 'selected' : '' }}>Itinerante
                                            </option>
                                            <option value="oyente"
                                                {{ request('condicion') == 'oyente' ? 'selected' : '' }}>Oyente
                                            </option>
                                        </select>

                                        <button type="submit" class="btn_blue">
                                            <i class="ti ti-file-export"
                                                style="font-size: 1.3em; margin-right: 8px;"></i>
                                            Aplicar filtros
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- cierre último bloque --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
</div> {{-- accordion --}}
</div>

<script>
    function toggleFiltroExportar(button) {
        const container = button.closest('div');
        const form = container.querySelector('.filtro-exportar');
        const isVisible = form.style.display === 'block';

        document.querySelectorAll('.filtro-exportar').forEach(f => f.style.display = 'none');

        if (!isVisible) {
            form.style.display = 'block';
        }
    }

    document.addEventListener('click', function(e) {
        const clickedInside = e.target.closest('.filtro-exportar') || e.target.closest(
            'button[onclick^="toggleFiltroExportar"]');
        if (!clickedInside) {
            document.querySelectorAll('.filtro-exportar').forEach(f => f.style.display = 'none');
        }
    });
</script>
@endsection