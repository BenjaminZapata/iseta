@php
use Illuminate\Support\Facades\Storage;

$path = $carrera->resolucion_archivo;
$existsStorage = $path && Storage::disk('public')->exists($path);
$existsPublic = $path && file_exists(public_path($path));
$hasFile = $existsStorage || $existsPublic;
$fileUrl = $existsStorage ? Storage::url($path) : ($existsPublic ? asset($path) : null);
@endphp



@extends('Admin.template')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/edit-carrera.css') }}">
<div class="edit-form-container">
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR CARRERA'])
        <div class="perfil__info">
            <form method="POST" action="{{ route('admin.carreras.update', ['carrera' => $carrera->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <fieldset class="p-2" style="margin:10px;">
                    <legend class="font-600 font-7">Información</legend>
                    <div class="grid-2 gap-2 p-0">
                        <label class="perfil_dataname">Nombre:
                            <p class="campo_info-noinput rounded"> {{ $carrera->nombre }} </p>
                        </label>

                        <label class="perfil_dataname">Resolución:
                            <p class="campo_info-noinput rounded"> {{ $carrera->resolucion }} </p>
                        </label>

                        <label class="perfil_dataname">Año de apertura:
                            <p class="campo_info-noinput rounded"> {{ $carrera->anio_apertura }} </p>
                            <input type="hidden" name="anio_apertura" value="{{ $carrera->anio_apertura }}">
                        </label>

                        <label class="label-input-y-75">Estado:
                            <select name="vigente" class="campo_info rounded">
                                <option value="{{ $carrera->vigente }}" selected>
                                    {{ $carrera->vigente ? 'Vigente' : 'No vigente' }}
                                </option>

                                @if ($carrera->vigente == 1)
                                @if (!empty($carrera->anio_fin))
                                <option value="0">No vigente</option>
                                @endif
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
                <fieldset class="p-3 archivo-fieldset">
                    <legend class="font-600 font-7">Archivo de la resolución</legend>

                    <div class="archivo-resolucion">
                        @if ($hasFile && $fileUrl)
                        <div class="archivo-actual">
                            <i class="ti ti-file-text archivo-icon"></i>
                            <a href="{{ $fileUrl }}" target="_blank" class="archivo-link">Ver PDF actual</a>
                        </div>

                        <div class="archivo-acciones">
                            <button type="button" class="btn_sky"
                                onclick="document.getElementById('resolucionInput').click()">
                                <i class="ti ti-refresh" style="font-size: 1.3em; margin-right: 8px;"></i>
                                Reemplazar
                            </button>
                            <input type="file" id="resolucionInput" name="resolucion_archivo_nuevo"
                                accept="application/pdf" hidden onchange="mostrarNombreArchivo(this)">

                            <div class="form-check form-check-danger">
                                <input type="checkbox" name="eliminar_resolucion_archivo" value="1"
                                    class="form-check-input" id="eliminarArchivo">
                                <label class="form-check-label" for="eliminarArchivo">
                                    <i class="ti ti-trash"></i> Eliminar actual
                                </label>
                            </div>
                        </div>

                        <div id="archivoPreview" class="archivo-preview"></div>
                        @else
                        <p class="archivo-vacio">No se ha cargado ningún archivo</p>
                        <button type="button" class="btn_exportar"
                            onclick="document.getElementById('resolucionInput').click()">
                            <i class="ti ti-upload"></i> Subir archivo
                        </button>
                        <input type="file" id="resolucionInput" name="resolucion_archivo_nuevo"
                            accept="application/pdf" hidden onchange="mostrarNombreArchivo(this)">
                        <div id="archivoPreview" class="archivo-preview bold"></div>
                        @endif

                        @error('resolucion_archivo_nuevo')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>


                <div class="botones-derecha">
                    <x-botones-alumno />
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
                    <form method="POST" id="form-eliminar-{{ $carrera->id }}"
                        action="{{ route('admin.carreras.destroy', ['carrera' => $carrera->id]) }}">
                        @csrf
                        @method('delete')
                        <button class="btn_red_outline" type="button"
                            onclick="openGeneralModal('form-eliminar-{{ $carrera->id }}', '¿Estás seguro de que querés eliminar la carrera: {{ strtoupper($carrera->apellido) }} {{ strtoupper($carrera->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                            class="btn_icon-danger" style="margin-left: 10px;">
                            <i class="ti ti-trash" style="font-size: 1.3em;"></i>Eliminar carrera
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
            {{-- <div style="position: relative;">
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
        </div> --}}

        {{-- Formularios ocultos --}}
        <form id="form-desactivar-{{ $carrera->id }}"
            action="{{ route('admin.carreras.desactivar', $carrera) }}" method="POST" style="display:none;">
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
                        {{-- <th class="center">Exportar</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @endif

                    <tr>
                        <td class="center">{{ $asignatura->anio }}</td>
                        <td>{{ $asignatura->nombre }}</td>
                        <td class="center">{{ $asignatura->carga_horaria }} horas</td>
                        <td style="display:flex; align-items: center; justify-content: center;">

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


                        </td>
                        <td>
                            <div style="display:flex; align-items: center; justify-content: center;">
                                {{-- <a href="{{ route('admin.mesas.dual', ['carrera' => $carrera->id, 'asignatura' => $asignatura->id]) }}"></a> --}}

                                <button class="btn_blue">
                                    <i class="ti ti-circle-plus" style="font-size: 1.3em; margin-right: 8px;"></i>
                                    Crear Mesa
                                </button>

                            </div>
                        </td>
                        {{-- <td>
                            <div style="display:flex; align-items: center; justify-content: center;">
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
        </td>--}}
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

    // Para ver el nombre antes de Guardar

    document.querySelector('input[name="resolucion_archivo_nuevo"]')
        ?.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const fileName = e.target.files[0].name;
                let preview = document.getElementById('archivoPreview');
                if (!preview) {
                    preview = document.createElement('p');
                    preview.id = 'archivoPreview';
                    preview.className = 'text-info mt-2';
                    e.target.parentNode.appendChild(preview);
                }
                preview.textContent = "Archivo seleccionado: " + fileName;
            }
        });

    function mostrarNombreArchivo(input) {
        const preview = document.getElementById('archivoPreview');
        if (input.files.length > 0) {
            preview.textContent = "Archivo seleccionado: " + input.files[0].name;
        } else {
            preview.textContent = "";
        }
    }
</script>
@endsection