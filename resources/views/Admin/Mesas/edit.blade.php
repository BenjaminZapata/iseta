@extends('Admin.template')
@php
use Illuminate\Support\HtmlString;

// Ensure selected professor variables are defined to avoid "use of unassigned variable" errors
$selectedPresidente = $selectedPresidente ?? 0;
$selectedVocal1 = $selectedVocal1;
$selectedVocal2 = $selectedVocal2;
@endphp

@section('content')
<div class="perfil_one br">
    <div class="edit-form-container">
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR MESA DE EXAMEN'])
            <div class="perfil__header">
                <h2>{{ $mesa->asignatura->carrera->first()->nombre ?? $mesa->asignatura->carrera->nombre }} / {{ $mesa->asignatura->nombre }}</h2>
            </div>

            {{-- Meta para pasar el ID de carrera al JS --}}
            <meta name="carrera-id" content="{{ $carrera_id }}">
            <form method="POST" action="{{ route('admin.mesas.update', ['mesa' => $mesa->id]) }}">
                @csrf
                @method('PUT')
                {!! $form->generate(null, 'put', [
                    'Profesores' => [
                        // Presidente
                        new HtmlString('
                            <div class="label-input-y-75">
                                <label>Presidente de mesa:</label>
                                <select name="prof_presidente" class="campo_info rounded">
                                    <option value="0" ' . ($selectedPresidente == 0 ? 'selected' : '') . '>Vacío/A confirmar</option>
                                    ' . implode('', array_map(fn($id, $nombre) => '<option value="' . $id . '" ' . ($selectedPresidente == $id ? 'selected' : '') . '>' . $nombre . '</option>', array_keys($opcionesProfesores), array_values($opcionesProfesores))) . '
                                </select>
                            </div>
                        '),
                        // Vocal 1
                        new HtmlString('
                            <div class="label-input-y-75">
                                <label>Vocal 1:</label>
                                <select name="prof_vocal_1" class="campo_info rounded"
                                    data-selected="' . $selectedVocal1 . '"
                                    data-selected-nombre="' . ($mesa->vocal1->apellido ?? '') . ' ' . ($mesa->vocal1->nombre ?? '') . '">
                                </select>
                            </div>
                        '),

                        // Vocal 2
                        new HtmlString('
                            <div class="label-input-y-75">
                                <label>Vocal 2:</label>
                                <select name="prof_vocal_2" class="campo_info rounded"
                                    data-selected="' . $selectedVocal2 . '"
                                    data-selected-nombre="' . ($mesa->vocal2->apellido ?? '') . ' ' . ($mesa->vocal2->nombre ?? '') . '">
                                </select>
                            </div>
                        '),
                    ],
                    // Llamado y fecha
                    'Llamado y Fecha' => [
                        new HtmlString('
                            <div class="label-input-y-75">
                                <label for="llamado">Llamado:</label>
                                <select class="campo_info rounded" name="llamado" id="llamado">
                                    <option value="1" ' . (old('llamado', $mesa->llamado) == 1 ? 'selected' : '') . '>Primero</option>
                                    <option value="2" ' . (old('llamado', $mesa->llamado) == 2 ? 'selected' : '') . '>Segundo</option>
                                </select>
                            </div>
                        '),
                        new HtmlString('
                            <div class="label-input-y-75">
                                <label for="fecha">Fecha del llamado:</label>
                                <input class="campo_info rounded" type="datetime-local" name="fecha" value="' . e(\Carbon\Carbon::parse($mesa->fecha)->format('Y-m-d\TH:i')) . '">
                            </div>
                        '),
                    ],
                    // Observaciones
                    'Otros' => [
                        $form->textarea('observaciones', 'Observaciones:', 'label-input-y-75',
                            old('observaciones', $mesa->observaciones ?? ''),
                            ['placeholder' => 'Notas adicionales', 'maxlength' => 150]
                        ),
                    ],
                ]) !!}
        </form>
        <div class="boton-eliminar">
                @if (!$config['modo_seguro'])
                    <div>
                        <form id="form-eliminar-{{ $mesa->id }}"
                            action="{{ route('admin.mesas.destroy', ['mesa' => $mesa->id]) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                onclick="openGeneralModal(
                                'form-eliminar-{{ $mesa->id }}',
                                '¿Estás seguro de que querés eliminar la mesa?\n\n' +
                                'Carrera: {{ $mesa->asignatura->carrera->first()->nombre ?? "No asignada" }}\n' +
                                'Asignatura: {{ $mesa->asignatura?->nombre ?? "No asignada" }}\n' +
                                'Fecha: {{ $mesa->fecha ? \Carbon\Carbon::parse($mesa->fecha)->format("d/m/Y") : "No definida" }}\n' +
                                'Presidente: {{ $mesa->profesor?->apellidoNombre() ?? "No asignado" }}\n' +
                                'Vocal 1: {{ $mesa->vocal1?->apellidoNombre() ?? "No asignado" }}\n' +
                                'Vocal 2: {{ $mesa->vocal2?->apellidoNombre() ?? "No asignado" }}\n\n' +
                                'estado: {{ $mesa->estado()[$mesa->estado] }}\n\n' +
                                'ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                class="btn_red_outline">
                                <i class="ti ti-trash" style="font-size: 1.3em; margin-right: 8px;"></i> Eliminar mesa
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
             <livewire:mesas-alumnos :mesa="$mesa" :inscribibles="$inscribibles" />

            </div>
        </div>
        <div class="table">
            <div class="table__header">
                <h2>Acta volante</h2>
                <div class="flex just-center">
                    <a href="{{ route('admin.mesas.acta', ['mesa' => $mesa->id]) }}" target="_blank">
                        <button class="btn_grey"><i class="ti ti-file-download"
                        style="font-size: 1.3em; margin-right: 8px;"></i>Regular</button>
                    </a>
                    <a href="{{ route('admin.mesas.actaprom', ['mesa' => $mesa->id]) }}" target="_blank"><button
                        class="btn_grey"><i class="ti ti-file-download"
                        style="font-size: 1.3em; margin-right: 8px;"></i>Promoción</button>
                    </a>
                    <a href="{{ route('admin.mesas.actalibre', ['mesa' => $mesa->id]) }}" target="_blank"><button
                        class="btn_grey"><i class="ti ti-file-download"
                        style="font-size: 1.3em; margin-right: 8px;"></i>Libre</button>
                    </a>
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
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                            <input name="nota" placeholder="0 = sin rendir, a = ausente" class="input-nota"
                                                value="{{ $examen->nota() }}">
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
                           <td class="center" style="min-width: 180px;">
                    <div style="display: flex; justify-content: center; gap:10px;">
                        <a href="{{ route('admin.examenes.edit', ['examen' => $examen->id]) }}">
    <button class="btn_blue btn_contraible">
        <i class="ti ti-pencil" style="font-size: 1.3em;"></i>
        <span class="btn-text">Editar</span>
    </button>
</a>
                        @if (!$config['modo_seguro'])
                        <div>
                            <form id="form-eliminar-{{ $mesa->id }}"
                                action="{{ route('admin.mesas.destroy', $mesa->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="btn_icon-danger btn_contraible" style="background-color: red;"
                                    onclick="openGeneralModal(
                                    'form-eliminar-{{ $mesa->id }}',
                                    '¿Estás seguro de que querés eliminar la mesa?\n\n' +
                                    'Carrera: {{ $mesa->asignatura->carrera->first()->nombre ?? "No asignada" }}\n' +
                                    'Asignatura: {{ $mesa->asignatura?->nombre ?? "No asignada" }}\n' +
                                    'Fecha: {{ $mesa->fecha ? \Carbon\Carbon::parse($mesa->fecha)->format("d/m/Y") : "No definida" }}\n' +
                                    'Presidente: {{ $mesa->profesor?->apellidoNombre() ?? "No asignado" }}\n' +
                                    'Vocal 1: {{ $mesa->vocal1?->apellidoNombre() ?? "No asignado" }}\n' +
                                    'Vocal 2: {{ $mesa->vocal2?->apellidoNombre() ?? "No asignado" }}\n\n' +
                                    'estado: {{ $mesa->estado()[$mesa->estado] }}\n\n' +
                                    'ESTA ACCIÓN NO SE PUEDE DESHACER.')">
                                    <i class="ti ti-trash" style="font-size: 1.3em"></i>
                                    <span class="btn-text">Eliminar</span>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('js/mesa-edit.js') }}"></script>
@endsection
