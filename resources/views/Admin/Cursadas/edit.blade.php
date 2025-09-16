@extends('Admin.template')

@section('content')
    {{-- <p class="w-100p">
    <a href="/admin/alumnos">Alumnos</a>/
    <a href="/admin/alumnos/{{$cursada->alumno->id}}/edit">{{$cursada->alumno->id}}</a>/ Cursada/
<a>{{$cursada->asignatura->nombre}}</a>
</p> --}}
    <div class="edit-form-container">
        <div class="perfil_one br">
            @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR CURSADA'])
            <div class="perfil__info">

                <form method="POST" action="{{ route('admin.cursadas.update', ['cursada' => $cursada->id]) }}">
                    @csrf
                    @method('put')
                    <div class="perfil_dataname">
                        <label>Carrera:</label>
                        <span class="campo_info2">{{ $cursada->asignatura->carrera->first()?->nombre }}</span>
                    </div>
                    <div class="perfil_dataname">
                        <label>Materia:</label>
                        <span class="campo_info2">{{ $cursada->asignatura->nombre }}</span>
                    </div>
                    <div class="perfil_dataname">
                        <label>Alumno/a:</label>
                        <span class="campo_info2">{{ $cursada->alumno->apellidoNombre() }}</span>
                    </div>
                    <div class="perfil_dataname">
                        <label>Año de cursada:</label>
                       <input class="campo_info rounded" value="{{$cursada->anio_cursada}}" name="anio_cursada">
                    </div>
                    <div x-data="{
                            condicion: '{{ (string) $cursada->condicion }}',
                            aprobada: '{{ (string) $cursada->aprobada }}'
                        }"
                        x-init="$watch('condicion', value => { if (value === '6') aprobada = null })">

                        <div class="perfil_dataname">
                            <label>Condicion:</label>
                            @php
                                $condiciones = [
                                    0 => 'Libre',
                                    1 => 'Regular',
                                    2 => 'Promocion',
                                    3 => 'Equivalencia',
                                    4 => 'Desertor',
                                    5 => 'Itinerante',
                                    6 => 'Oyente',
                                ];

                                $condicionesExcluidas = [2, 3, 4];
                                $condicionActual = $cursada->condicion;
                            @endphp

                            <select class="campo_info rounded" name="condicion" x-model="condicion">
                                @if (in_array($condicionActual, $condicionesExcluidas))
                                    <option value="{{ $condicionActual }}" selected hidden>
                                        {{ $condiciones[$condicionActual] }}
                                    </option>
                                @endif

                                @foreach ($condiciones as $valor => $texto)
                                    @if (!in_array($valor, $condicionesExcluidas))
                                        <option value="{{ $valor }}" @selected($condicionActual == $valor)>
                                            {{ $texto }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Estado (solo aparece si no es Oyente) --}}
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
                                    <input class="campo_info rounded" value="{{ $nota }}" name="nota" type="number" />
                                </div>
                            </div>
                    </template>

                        {{-- Hidden para mandar null si condicion es Oyente --}}
                        <template x-if="condicion === '6'">
                            <input type="hidden" name="aprobada" :value="null">
                        </template>
                    </div>
                    <input type="hidden" value="{{ url()->previous() }}" name="redirect">

                    <div class="botones-derecha"
                        style="margin-right: 27px; padding-top: 10px; display: flex; gap: 12px; justify-content: flex-end;">
                        <x-btn-cancelar />
                        <button type="submit" class="btn_blue">
                            <i class="ti ti-refresh" style="font-size: 1.3em; margin-right: 8px;"></i>
                            Actualizar
                        </button>
                    </div>
                </form>
                <div class="botones-derecha">
                    @if (!$config['modo_seguro'])
                        <div>
                            <form id="form-eliminar-{{ $cursada->id }}"
                                action="{{ route('admin.cursadas.destroy', $cursada->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="openGeneralModal('form-eliminar-{{ $cursada->id }}',
                                    '¿Estás seguro de que querés eliminar la cursada de la asignatura:  {{ strtoupper($cursada->asignatura->nombre) }} de la carrera {{ strtoupper($cursada->carrera->nombre) }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                                    class="btn_red_outline">
                                    <i class="ti ti-trash" style="font-size: 1.3em; margin-right: 8px;"></i> Eliminar
                                    cursada
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
