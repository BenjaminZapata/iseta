@extends('Admin.template')

@section('content')
    <div class="edit-form-container">
        <div class="perfil_one br">
            <div class="perfil__header">
                <h2>Carrera</h2>
            </div>
            <div class="perfil__info">
                <?= $form->generate(route('admin.carreras.update', ['carrera' => $carrera->id]), 'put', [
        'Información' => [
            $form->text('nombre', 'Nombre:', 'label-input-y-75', $carrera),
            $form->text('resolucion', 'Resolucion:', 'label-input-y-75', $carrera),
            $form->text('anio_apertura', 'Año de apertura:', 'label-input-y-75', $carrera),
            $form->text('anio_fin', 'Año de cierre:', 'label-input-y-75', $carrera),
            $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', $carrera),
            $form->texthidden(url()->previous())
        ]
    ]) ?>
            </div>
        </div>

        <div class="table">
            <div class="perfil__header-alt">
                <a href="{{ route('admin.carreras.add', ['carrera' => $carrera->id]) }}">
                    <button class="btn_blue"><i class="ti ti-circle-plus"></i>Agregar asignatura</button>
                </a>
                <a href="{{ route('admin.asignaturas.create') }}">
                    <button class="btn_blue"><i class="ti ti-circle-plus"></i>Crear asignatura</button>
                </a>

                {{-- BOTÓN GENERAL DE EXPORTACIÓN --}}
                <div style="position: relative;">
                    <button type="button" class="btn_blue" onclick="toggleFiltroExportar(this)">
                        <i class="ti ti-file-download"></i> Exportar cursadas
                    </button>

                    <form method="GET" action="{{ route('excel.cursadas.carrera', ['carrera' => $carrera->id]) }}"
                        class="filtro-exportar"
                        style="display: none; position: absolute; top: 100%; left: 0; background: #fff; border: 1px solid #ccc; padding: 10px; z-index: 10; width: max-content; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">

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
                                <option value="regular" {{ request('condicion') == 'regular' ? 'selected' : '' }}>Regular
                                </option>
                                <option value="libre" {{ request('condicion') == 'libre' ? 'selected' : '' }}>Libre</option>
                                <option value="promocion" {{ request('condicion') == 'promocion' ? 'selected' : '' }}>
                                    Promoción</option>
                                <option value="equivalencia" {{ request('condicion') == 'equivalencia' ? 'selected' : '' }}>
                                    Equivalencia</option>
                                <option value="desertor" {{ request('condicion') == 'desertor' ? 'selected' : '' }}>Desertor
                                </option>
                                <option value="itinerante" {{ request('condicion') == 'itinerante' ? 'selected' : '' }}>
                                    Itinerante</option>
                                <option value="oyente" {{ request('condicion') == 'oyente' ? 'selected' : '' }}>Oyente
                                </option>
                            </select>

                            <button type="submit" class="btn_blue">
                                <i class="ti ti-file-export"></i> Aplicar filtros
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <table class="table__body">
                <thead>
                    <tr>
                        <th class="center">Año</th>
                        <th>Materia</th>
                        <th class="center">Carga anual/semanal</th>
                        <th class="center">Acción</th>
                        <th class="center" colspan="2">Crear</th>
                        <th class="center">Exportar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carrera->asignaturas as $asignatura)
                        <tr>
                            <td class="center">{{ $asignatura->anio }}</td>
                            <td>{{ $asignatura->nombre }}</td>
                            <td class="center">{{ $asignatura->carga_horaria }} horas</td>
                            <td style="display:flex;">
                                <form action="{{ route('admin.asignaturas.edit', ['asignatura' => $asignatura->id]) }}">
                                    <button class="btn_blue"><i class="ti ti-edit"></i>Editar</button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.mesas.create') }}">
                                    <input name="carrera" type="hidden" value="{{ $carrera->id }}">
                                    <input name="asignatura" type="hidden" value="{{ $asignatura->id }}">
                                    <button class="btn_blue"><i class="ti ti-circle-plus"></i>Mesa</button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.mesas.dual', ['asignatura' => $asignatura->id]) }}">
                                    <button class="btn_blue"><i class="ti ti-circle-plus"></i>Mesas</button>
                                </a>
                            </td>
                            <td>
                                <div style="position: relative;">
                                    <button type="button" class="btn_blue" onclick="toggleFiltroExportar(this)">
                                        <i class="ti ti-file-download"></i> Exportar cursadas
                                    </button>

                                    <form method="GET"
                                        action="{{ route('excel.cursadas.carrera', ['carrera' => $carrera->id]) }}"
                                        class="filtro-exportar"
                                        style="display: none; position: absolute; top: 100%; left: 0; background: #fff; border: 1px solid #ccc; padding: 10px; z-index: 10; width: max-content; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">

                                        <input type="hidden" name="asignatura_id" value="{{ $asignatura->id }}">

                                        <div style="display: flex; flex-direction: column; gap: 8px;">
                                            <select name="genero">
                                                <option value="">-- Género --</option>
                                                <option value="f" {{ request('genero') == 'f' ? 'selected' : '' }}>Femenino
                                                </option>
                                                <option value="m" {{ request('genero') == 'm' ? 'selected' : '' }}>Masculino
                                                </option>
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
                                                <option value="regular" {{ request('condicion') == 'regular' ? 'selected' : '' }}>
                                                    Regular</option>
                                                <option value="libre" {{ request('condicion') == 'libre' ? 'selected' : '' }}>
                                                    Libre</option>
                                                <option value="promocion" {{ request('condicion') == 'promocion' ? 'selected' : '' }}>Promoción</option>
                                                <option value="equivalencia" {{ request('condicion') == 'equivalencia' ? 'selected' : '' }}>Equivalencia</option>
                                                <option value="desertor" {{ request('condicion') == 'desertor' ? 'selected' : '' }}>Desertor</option>
                                                <option value="itinerante" {{ request('condicion') == 'itinerante' ? 'selected' : '' }}>Itinerante</option>
                                                <option value="oyente" {{ request('condicion') == 'oyente' ? 'selected' : '' }}>
                                                    Oyente</option>
                                            </select>

                                            <button type="submit" class="btn_blue">
                                                <i class="ti ti-file-export"></i> Aplicar filtros
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if (!$config['modo_seguro'])
            <div class="upd">
                <form method="POST" class="form-eliminar"
                    action="{{ route('admin.carreras.destroy', ['carrera' => $carrera->id]) }}">
                    @csrf
                    @method('delete')
                    <button class="btn_red"><i class="ti ti-trash"></i>Eliminar carrera</button>
                </form>
            </div>
        @endif
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

        document.addEventListener('click', function (e) {
            const clickedInside = e.target.closest('.filtro-exportar') || e.target.closest('button[onclick^="toggleFiltroExportar"]');
            if (!clickedInside) {
                document.querySelectorAll('.filtro-exportar').forEach(f => f.style.display = 'none');
            }
        });
    </script>
@endsection