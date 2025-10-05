<div class="space-y-6">

    {{-- Mensaje de éxito --}}
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Selección de carreras --}}
    <div style="display: flex; flex-direction: column; gap: 8px;">
        <h3 class="mb-3">Seleccionar carrera/s</h3>
        <select wire:model="carrerasSeleccionadas" multiple class="form-control select-carreras">
            @foreach ($carreras as $carrera)
                <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
            @endforeach
        </select>
        <small class="form-text text-muted">Usá Ctrl (Windows) o Cmd (Mac) para seleccionar múltiples carreras.</small>
    </div>

    {{-- Listado de asignaturas --}}
    <div id="contenedorTablas" class="mt-5">
        @foreach ($this->carrerasConAsignaturas as $carrera)
            <div class="card mb-5 shadow-sm p-3">
                <h4 class="mb-4 text-primary border-bottom pb-2">{{ $carrera->nombre }}</h4>

                @php
                    $agrupadas = $carrera->asignaturas->groupBy('anio');
                    $anioIndex = 0;
                @endphp

                @foreach ($agrupadas->sortKeys() as $anio => $asignaturas)
                    @php $anioIndex++; @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button type="button"
                                onclick="document.getElementById('collapse{{ $carrera->id }}-{{ $anioIndex }}').classList.toggle('hidden')"
                                class="accordion-button collapsed font-500 w-full text-left px-4 py-2 border-b">
                                {{ $anio ?? 'Sin año' }}° año
                            </button>
                        </h2>
                        <div id="collapse{{ $carrera->id }}-{{ $anioIndex }}"
                            class="accordion-collapse collapse hidden">
                            <div class="accordion-body p-0">
                                <table class="table table-bordered table-hover mb-0 w-full">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 40%;">Asignatura</th>
                                            <th class="center" style="width: 10%;">Asignar</th>
                                            <th style="width: 15%;">Año</th>
                                            <th style="width: 20%;">Tipo módulo</th>
                                            {{-- Agrega más columnas aquí si necesitas --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asignaturas as $asig)
                                            <tr>
                                                <td class="align-middle bold">{{ $asig->nombre }}</td>
                                                <td class="text-center align-middle">
                                                    <input type="checkbox"
                                                        wire:model="asignaturasSeleccionadas.{{ $asig->id }}.checked"
                                                        wire:change="$set('asignaturasSeleccionadas.{{ $asig->id }}.id_carrera', {{ $carrera->id }})">
                                                </td>
                                                <td>
                                                    <input type="number" min="1"
                                                        wire:model.defer="asignaturasSeleccionadas.{{ $asig->id }}.anio"
                                                        class="form-control" placeholder="Año">
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        wire:model.defer="asignaturasSeleccionadas.{{ $asig->id }}.tipo_modulo"
                                                        class="form-control" placeholder="Tipo módulo">
                                                </td>
                                                {{-- Más campos si es necesario --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- Botón guardar --}}
    <div class="flex justify-end mt-4">
        <button wire:click="guardarVinculaciones"
            class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
            Guardar asignaturas
        </button>
    </div>

    {{-- Estilos --}}
    <style>
        .select-carreras {
            font-size: 1.1em;
            min-height: 180px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #fafafa;
        }

        .select-carreras option {
            padding: 6px 10px;
        }

        .select-carreras:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            background-color: #fff;
        }
    </style>
</div>
