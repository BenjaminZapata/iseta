@extends('Admin.template')
@php
$admin = Auth::guard('admin')->user();
$soloLectura = $admin->rol == 2; // true si es secretario
@endphp
<link rel="stylesheet" href="{{ asset('css/Admin/Cursadas/edit-cursadas.css') }}">

@section('content')
<div class="edit-form-container">
    <div class="perfil_one br">
        @include('components.header-avatar', ['tituloSeccion' => 'MODIFICAR CURSADA'])
        <div class="perfil__info">

            <form method="POST"
                action="{{ route('admin.cursadas.update', ['cursada' => $cursada->id]) }}"
                @if($soloLectura) onsubmit="return false;" @endif>
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
                    @if($soloLectura)
                    <span class="campo_info2">{{ $cursada->anio_cursada }}</span>
                    @else
                    <input class="campo_info rounded" value="{{ $cursada->anio_cursada }}" name="anio_cursada">
                    @endif
                </div>

                <div x-data="{
                        condicion: '{{ (string) $cursada->condicion }}',
                        aprobada: '{{ (string) $cursada->aprobada }}'
                    }" x-init="$watch('condicion', value => { if (value === '6') aprobada = null })">

                    <div class="perfil_dataname">
                        <label>Condición:</label>
                        @php
                        $condiciones = [
                        0 => 'Libre',
                        1 => 'Regular',
                        2 => 'Promoción',
                        3 => 'Equivalencia',
                        4 => 'Desertor',
                        5 => 'Itinerante',
                        6 => 'Oyente',
                        ];
                        $condicionesExcluidas = [2, 3, 4];
                        $condicionActual = $cursada->condicion;
                        @endphp

                        @if($soloLectura)
                        <span class="campo_info2">{{ $condiciones[$condicionActual] ?? '-' }}</span>
                        @else
                        <select class="campo_info rounded" name="condicion" x-model="condicion">
                            @if (in_array($condicionActual, $condicionesExcluidas))
                            <option value="{{ $condicionActual }}" selected hidden>
                                {{ $condiciones[$condicionActual] }}
                            </option>
                            @endif

                            @foreach ($condiciones as $valor => $texto)
                            @if (!in_array($valor, $condicionesExcluidas))
                            <option value="{{ $valor }}" @selected($condicionActual==$valor)>
                                {{ $texto }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                        @endif
                    </div>

                    <div class="perfil_one br">
                        <div class="perfil__header" style="cursor: pointer;">
                            <h3>Ficha de cursada</h3>
                            <i class="ti ti-chevron-left iconos"></i>
                        </div>

                        <div class="detalle-cursada hidden">
                            @if(!$soloLectura)
                            <template x-if="condicion === '6'">
                                <input type="hidden" name="aprobada" :value="null">
                            </template>
                            @endif

                            <div class="detalle-item perfil_dataname">
                                <label>Estado:</label>
                                @if($soloLectura)
                                <span class="campo_info2">
                                    @php
                                    $estados = [
                                    1 => 'Aprobada',
                                    2 => 'Desaprobada',
                                    3 => 'Cursando',
                                    4 => 'Promocionada',
                                    5 => 'Equivalencia',
                                    ];
                                    @endphp
                                    {{ $estados[$cursada->aprobada] ?? '-' }}
                                </span>
                                @else
                                <select class="campo_info rounded" name="aprobada" x-model="aprobada">
                                    <option value="1">Aprobada</option>
                                    <option value="2">Desaprobada</option>
                                    <option value="3">Cursando</option>
                                    <option value="4">Promocionada</option>
                                    <option value="5">Equivalencia</option>
                                </select>
                                @endif
                            </div>

                            <div class="detalle-item perfil_dataname">
                                <label>Nota 1.er Cuatrimestre:</label>
                                @if($soloLectura)
                                <span class="campo_info2">{{ $cursada->primer_cuatrimestre_nota }}</span>
                                @else
                                <input class="campo_info rounded"
                                    value="{{ old('primer_cuatrimestre_nota') ?? $cursada->primer_cuatrimestre_nota }}"
                                    name="primer_cuatrimestre_nota">
                                @endif
                            </div>

                            <div class="detalle-item perfil_dataname">
                                <label>Nota 2.do Cuatrimestre:</label>
                                @if($soloLectura)
                                <span class="campo_info2">{{ $cursada->segundo_cuatrimestre_nota }}</span>
                                @else
                                <input class="campo_info rounded"
                                    value="{{ old('segundo_cuatrimestre_nota') ?? $cursada->segundo_cuatrimestre_nota }}"
                                    name="segundo_cuatrimestre_nota">
                                @endif
                            </div>

                            <div class="detalle-item perfil_dataname">
                                <label>Observaciones:</label>
                                @if($soloLectura)
                                <span class="campo_info2">{{ $cursada->observaciones }}</span>
                                @else
                                <input class="campo_info rounded"
                                    value="{{ old('observaciones') ?? $cursada->observaciones }}"
                                    name="observaciones">
                                @endif
                            </div>
                        </div>
                    </div>

                    <input type="hidden" value="{{ url()->previous() }}" name="redirect">

                    {{-- BOTONES --}}
                    <div class="botones-derecha"
                        style="margin-right: 27px; padding-top: 10px; display: flex; gap: 12px; justify-content: flex-end;">
                        <x-btn-cancelar />
                        @if (!$soloLectura && in_array($admin->rol, [0,1]))
                        <button type="submit" class="btn_blue">
                            <i class="ti ti-refresh" style="font-size: 1.3em; margin-right: 8px;"></i>
                            Actualizar
                        </button>
                        @endif
                    </div>
                </div>
            </form>

            {{-- BOTÓN ELIMINAR --}}
            <div class="boton-eliminar">


                @if (!$soloLectura && in_array($admin->rol, [0,1]) && !$config['modo_seguro'])
                <div class="botones-derecha">
                    <form id="form-eliminar-{{ $cursada->id }}"
                        action="{{ route('admin.cursadas.destroy', $cursada->id) }}"
                        method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            onclick="openGeneralModal('form-eliminar-{{ $cursada->id }}',
                                '¿Estás seguro de que querés eliminar la cursada del alumno: {{ strtoupper($cursada->alumno->apellidoNombre()) }} de la asignatura:  {{ strtoupper($cursada->asignatura->nombre ?? 'Sin Asignatura') }} de la carrera {{ strtoupper($cursada->carrera->nombre ?? 'Sin Carrera') }}? \n \n ESTA ACCIÓN NO SE PUEDE DESHACER.')"
                            class="btn_red_outline">
                            <i class="ti ti-trash" style="font-size: 1.3em; margin-right: 8px;"></i> Eliminar cursada
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script defer>
    document.addEventListener('click', (e) => {
        const header = e.target.closest('.perfil__header');
        if (!header) return;
        const perfil = header.closest('.perfil_one');
        const detalle = perfil?.querySelector('.detalle-cursada');
        if (!detalle) return;
        detalle.classList.toggle('hidden');
        header.querySelector('.iconos')?.classList.toggle('rotated');
    });
</script>
@endsection