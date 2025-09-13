@extends('Preceptor.template')

@section('content')
<div class="edit-form-container">
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR CARRERA'])
        <div class="perfil__info">
            {{-- BOTÓN SWITCH DE ESTADO --}}
            <div style="margin: 10px 5px; display: flex; align-items: center; gap: 12px; justify-content: flex-end; padding-right: 25px;">
                <span style="font-weight: 600;">
                    Estado de la carrera:
                    <span id="estado-texto" style="color: {{ $carrera->vigente ? '#16a34a' : '#dc2626' }};">
                        {{ $carrera->vigente ? 'Activa' : 'Inactiva' }}
                    </span>
                </span>

                <label class="switch" title="{{ $carrera->vigente ? 'Desactivar carrera' : 'Activar carrera' }}">
                    <input type="checkbox"
                        id="toggle-carrera-{{ $carrera->id }}"
                        @if ($carrera->vigente) checked @endif
                    onchange="onToggleCarrera(this, {{ $carrera->id }})">
                    <span class="slider"></span>
                </label>
            </div>
            <?= $form->generate(route('preceptor.carreras.update', ['carrera' => $carrera->id]), 'put', [
                'Información' => [
                    $form->text('nombre', 'Nombre:', 'label-input-y-75', $carrera),
                    $form->text('resolucion', 'Resolucion:', 'label-input-y-75', $carrera),
                    $form->text('anio_apertura', 'Año de apertura:', 'label-input-y-75', $carrera),
                    $form->text('anio_fin', 'Año de cierre:', 'label-input-y-75', $carrera),
                    $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', $carrera),
                    $form->texthidden(url()->previous())
                ],
            ]) ?>
            <div class="boton-eliminar">
                @if (!$config['modo_seguro'])
                <div>
                    <form method="POST" class="form-eliminar"
                        action="{{ route('preceptor.carreras.destroy', ['carrera' => $carrera->id]) }}">
                        @csrf
                        @method('delete')
                        <button class="btn_red_outline"
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
            <a href="{{ route('preceptor.carreras.addAsignaturaView', ['carrera' => $carrera->id]) }}">
                <button class="btn_blue"><i class="ti ti-circle-plus"
                        style="font-size: 1.3em; margin-right: 8px;"></i>Agregar asignatura</button>
            </a>
            <a href="{{ route('admin.carreras.createAsignaturaView', ['carrera' => $carrera->id]) }}">
                <button class="btn_blue"><i class="ti ti-circle-plus"
                        style="font-size: 1.3em; margin-right: 8px;"></i>Crear asignatura</button>
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
                            <option value="regular" {{ request('condicion') == 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="libre" {{ request('condicion') == 'libre' ? 'selected' : '' }}>Libre</option>
                            <option value="promocion" {{ request('condicion') == 'promocion' ? 'selected' : '' }}>Promoción</option>
                            <option value="equivalencia" {{ request('condicion') == 'equivalencia' ? 'selected' : '' }}>Equivalencia</option>
                            <option value="desertor" {{ request('condicion') == 'desertor' ? 'selected' : '' }}>Desertor</option>
                            <option value="itinerante" {{ request('condicion') == 'itinerante' ? 'selected' : '' }}>Itinerante</option>
                            <option value="oyente" {{ request('condicion') == 'oyente' ? 'selected' : '' }}>Oyente</option>
                        </select>

                        <button class="btn_blue">
                            <i class="ti ti-file-export" style="font-size: 1.3em; margin-right: 8px;"></i> Descargar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Formularios ocultos --}}
            <form id="form-desactivar-{{ $carrera->id }}"
                action="{{ route('admin.preceptor.desactivar', $carrera) }}" method="POST" style="display:none;">
                @csrf
            </form>
            <form id="form-reactivar-{{ $carrera->id }}"
                action="{{ route('admin.preceptor.reactivar', $carrera) }}" method="POST" style="display:none;">
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
        <button class="accordion-button collapsed font-500" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapseAnio{{ $anio_index }}"
            aria-expanded="false"
            aria-controls="collapseAnio{{ $anio_index }}">
            {{ $anio_actual }}° año
        </button>
    </h2>
    <div id="collapseAnio{{ $anio_index }}" class="accordion-collapse collapse"
        aria-labelledby="headingAnio{{ $anio_index }}"
        data-bs-parent="#asignaturasAccordion">
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
                            <form action="{{ route('admin.preceptor.edit', ['asignatura' => $asignatura->id]) }}">
                                <button class="btn_blue"><i class="ti ti-edit"
                                        style="font-size: 1.3em; margin-right: 8px;"></i>Editar</button>
                            </form>
                        </td>
                        <td>
                            <div style="display:flex; align-items: center; justify-content: center;">
                                <a href="{{ route('preceptor.mesas.dual', ['carrera' => $carrera->id, 'asignatura' => $asignatura->id]) }}">
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
                                            <option value="regular" {{ request('condicion') == 'regular' ? 'selected' : '' }}>Regular</option>
                                            <option value="libre" {{ request('condicion') == 'libre' ? 'selected' : '' }}>Libre</option>
                                            <option value="promocion" {{ request('condicion') == 'promocion' ? 'selected' : '' }}>Promoción</option>
                                            <option value="equivalencia" {{ request('condicion') == 'equivalencia' ? 'selected' : '' }}>Equivalencia</option>
                                            <option value="desertor" {{ request('condicion') == 'desertor' ? 'selected' : '' }}>Desertor</option>
                                            <option value="itinerante" {{ request('condicion') == 'itinerante' ? 'selected' : '' }}>Itinerante</option>
                                            <option value="oyente" {{ request('condicion') == 'oyente' ? 'selected' : '' }}>Oyente</option>
                                        </select>

                                        <button type="submit" class="btn_blue">
                                            <i class="ti ti-file-export" style="font-size: 1.3em; margin-right: 8px;"></i>
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